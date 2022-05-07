
# CS4624_Figure_Extraction_Website
Virginia Tech CS 4624 Project Website for Figure Extraction using PDFFigures2, PDFPlumber, and Elasticsearch in Python and PHP

<br />

If on Linux continue, else if on Windows, set up WSL and install Ubuntu
* https://docs.microsoft.com/en-us/windows/wsl/install
* https://ubuntu.com/wsl

<br />

First, navigate to your /var/www/html/ folder and create a directory named "figures" and then clone this GitHub repository into the new "figures" directory.
```bash
git clone https://github.com/JRReynosa/CS4624_Figure_Extraction_Website figures
```

<br />

Install apache2 and then start it:
* https://ubuntu.com/tutorials/install-and-configure-apache#2-installing-apache

Run apache2 by typing:
```bash
sudo service apache2 start
```

<br />

Install Elasticsearch and then start it.
* https://www.digitalocean.com/community/tutorials/how-to-install-and-configure-elasticsearch-on-ubuntu-18-04

Run Elasticsearch by typing:
```bash
sudo service elasticsearch start
```
To test Elasticsearch is running use:
```bash
curl -X GET "localhost:9200"
```

<br />

Optionally, install Kibana and then start it.
```bash
sudo apt install --assume-yes kibana
```
```bash
sudo service kibana start
```
Kibana gives a GUI to view Elasticsearch data which can be accessed at http://localhost:5601/.

<br />

Download the processesd json [zip file](https://drive.google.com/file/d/1yP705eq-FasesXylMfnTKYM71Zje1IpM/view?usp=sharing) and then extract it into a directory. Next, go into the extracted directory and run the following command:
```bash
bash script.bash
```
This indexes all of the Elasticsearch entries, ignore the last index as it is indexing the bash script which cannot be indexed.

<br />

Download the images [zip file](https://drive.google.com/file/d/1RRa35hbX0dsXvJLdNtgRVGlbN3Nuok_C/view?usp=sharing) and then extract it into the /var/www/html/figures/src/figure_extraction/images/ directory.

This provides a path to all already processed Elasticsearch entries.

<br />

Optionally, to use the upload feature of the website one needs to install Java SDK and sbt, it is suggested to use SDKMAN! 
1. https://sdkman.io/install
2. https://www.scala-sbt.org/1.x/docs/Installing-sbt-on-Linux.html



## Using the website
Run the demo with the following command in the /var/www/html/ directory:
```bash
php -S localhost:8000 -t figures 
```
command, access the website through http://127.0.0.1:8000/ or http://localhost:8000/.

Before entering the website, you may need to enter the admin password: 313ctr1c1+y

