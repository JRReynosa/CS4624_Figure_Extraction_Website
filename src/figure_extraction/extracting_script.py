# Script for pipeline that runs pdffigures2 in Python rather than running sbt command
# in a Command Prompt, and then we run the output in pdfplumber to extract more figures.
# The point of the pipeline is to maximize figure output, while also accounting for
# duplicate images.

# -------------------------------------- pdffigures2 -------------------------------------- #
import os
import json
import ndjson
from elasticsearch import Elasticsearch
from elasticsearch.helpers import bulk

# Batch PDF Command:
# sbt "runMain org.allenai.pdffigures2.FigureExtractorBatchCli 
# /var/www/html/figures/src/figure_extraction/pdf_files
# -s stat_file.json -m /var/www/html/figures/src/figure_extraction/images/
# -d /var/www/html/figures/src/figure_extraction/pf2_jsonoutput/"
#
# The command runs the pdfs in the ~\pdf folder in a batch, and all data is outputted to
# the respective folders defined below. For each pdf, there is n .png files outputted and a .json file
# that contains metadata on its respective ETD. Lastly, there is a stat_file.json that contains
# metadata on all files processed.

# Paths
pdf_path = "/var/www/html/figures/src/figure_extraction/pdf_files"
pf2_image_output_path = "/var/www/html/figures/src/figure_extraction/images/"
pf2_json_output_path = "/var/www/html/figures/src/figure_extraction/pf2_jsonoutput/"
pf2_scala_path = "/var/www/html/figures/src/figure_extraction/pdffigures2"

# # Create output directory if it does not exist
# if not os.path.isdir(pf2_output_path):
#     os.mkdir(pf2_output_path)

# Build command
command = ' '.join(['runMain', 'org.allenai.pdffigures2.FigureExtractorBatchCli', pdf_path,
                #'-s', pf2_output_path + 'pdffigures2_stats.json', # stats that we did not use
                '-m', pf2_image_output_path,
                '-d', pf2_json_output_path])
sbt_command = ' '.join(['sbt', '"' + command + '"'])

# Change directory and run Scala command
os.chdir(pf2_scala_path)
os.system(sbt_command)

# Function to generate formatted data for Elasticsearch entry
def data_generator(data):
        for entry in data:
            doc = {
                "_index": "figures",
                "caption": entry["caption"],
                "captionBoundary": entry["captionBoundary"],
                "figType": entry["figType"],
                "imageText": entry["imageText"],
                "name": entry["name"],
                "page": entry["page"],
                "regionBoundary": entry["regionBoundary"],
                "renderDpi": entry["renderDpi"],
                "renderURL": entry["renderURL"],
            }
            yield doc

# Convert to ndjson format
elasticsearch_path = "C:/Users/jonny/Desktop/CS4624/elasticsearch/"
for filename in os.listdir(pdf_path):
    filename = filename[:len(filename) - 4] # Remove .pdf extension
    pf2_jsonfile = open(pf2_json_output_path + filename + '.json', encoding="utf8")
    json_data = json.load(pf2_jsonfile)
    ndjson_text = ndjson.dumps(json_data)
    ndjson_data = ndjson.loads(ndjson_text)

    # Index in Elasticsearch
    es = Elasticsearch("http://localhost:9200") # localhost & port 9200 Since we are running locally
    bulk(es, data_generator(ndjson_data))

    pf2_jsonfile.close()

# Delete PDF and JSON Files
for f in os.listdir(pf2_json_output_path):
    os.remove(pf2_json_output_path + f)

for f in os.listdir(pdf_path):
    os.remove(pdf_path + '/' + f)

# -------------------------------------- pdffigures2 -------------------------------------- #
import pdfplumber

# Iterate through files already processed by pdffigures2, and we take into account images 
# already processed to avoid duplicates.

def outside_page_bound(image_obj, page):
    if (image_obj['x0'] > page.bbox[2] or image_obj['x1'] > page.bbox[2] or
        image_obj['top'] > page.bbox[3] or image_obj['bottom'] > page.bbox[3] or
        image_obj['x0'] < 0 or image_obj['x1'] < 0 or
        image_obj['top'] < 0 or image_obj['bottom'] < 0):
            return True
    return False

def is_duplicate(filename, image_obj):
    # Opening JSON file
    f = open(pf2_json_output_path + filename + '.json', encoding="utf8")
    pf2_json = json.load(f)

    # Iterate figures processed by pdffigures2
    for fig in pf2_json:

        if (fig['page'] + 1 != image_obj["page_number"]):
            continue

        pf2_boundary = fig["regionBoundary"]

        # Check if boundaries are too close, meaning they're duplcates
        if (abs(pf2_boundary['x1'] - image_obj['x0']) < 2
            and abs(pf2_boundary['x2'] - image_obj['x1']) < 2
            and abs(pf2_boundary['y1'] - image_obj['top']) < 2
            and abs(pf2_boundary['y2'] - image_obj['bottom']) < 2):
            return True

        # Check if PdfPlumber image is inside of PdfFigures2 image
        if ((image_obj['x0'] >= pf2_boundary['x1']) and (image_obj['x1'] <= pf2_boundary['x2']) and 
            (image_obj['top'] >= pf2_boundary['y1']) and (image_obj['bottom'] <= pf2_boundary['y2'])):
            return True

        # Check for intersection
        if (pf2_boundary['x1'] < image_obj['x1'] and pf2_boundary['x2'] > image_obj['x0'] and 
            pf2_boundary['y1'] < image_obj['bottom'] and pf2_boundary['y2'] > image_obj['top']):
            return True
    
    return False


# # Iterate over files in pdf directory
# pp_output_path = "C:/Users/jonny/Desktop/CS4624/pdfplumberoutput/"
# file_meta_list = []
# for filename in os.listdir(pdf_path):
#     pdf = pdfplumber.open(pdf_path + "/" + filename)
#     filename = filename[:len(filename) - 4] # Remove .pdf extension
#     print("\nProcessing file: " + filename)

#     # Add pdf to collective stats json file
#     file_meta_list.append(pdf.metadata)

#     img_meta_list = []

#     for p in range(0, len(pdf.pages)): # Iterate through pages
#         print("Processing page " + str(p) + " of " + str(len(pdf.pages) - 1) + " in file " + filename)
#         page = pdf.pages[p]
#         for i in range(0, len(page.images)): # Iterate through images in page
#             image_obj = page.images[i]

#             # If image bounds outside PDF size, skip
#             if (outside_page_bound(image_obj, page)):
#                 continue

#             # If duplicate from figure processed by pdffigures2
#             if (is_duplicate(filename, image_obj)):
#                 continue

#             # If image is too small, skip
#             if (image_obj['x1'] - image_obj['x0'] < 20 or
#                 image_obj['bottom'] - image_obj['top'] < 20):
#                 continue
            
#             # Add image metadata to respective pdf json file
#             image_obj.pop('stream') # Cannot be serialized; remove from dict
#             image_obj.pop('colorspace') # Cannot be serialized; remove from dict
#             img_meta_list.append(image_obj)

#             # Crop and save image
#             bbox = (image_obj['x0'], image_obj['top'], image_obj['x1'], image_obj['bottom'])
#             cropped_page = page.within_bbox(bbox)
#             img_name = image_obj['name']
#             image_obj = cropped_page.to_image(resolution=200)
#             image_obj.save(pp_output_path + filename + "_" + "Pg" + str(p) + img_name + ".png", format="PNG")

#     # Dump image metadata to file
#     if (len(img_meta_list) != 0):
#         # Writing items to a ndjson file
#         with open(pp_output_path + filename + '.ndjson', 'w') as f:
#             writer = ndjson.writer(f, ensure_ascii=False)
#             for img in img_meta_list:
#                 writer.writerow(img)

# # Dump pdf metadata to file
# if (len(file_meta_list) != 0):
#     # Writing items to a ndjson file
#     with open(pp_output_path + 'pdfplumber_stats.ndjson', 'w') as f:
#         writer = ndjson.writer(f, ensure_ascii=False)
#         for file in file_meta_list:
#             writer.writerow(file)

print("All PDFs in directory have been processed.")
