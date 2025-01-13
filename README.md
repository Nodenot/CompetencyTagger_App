# Skill Tagger Application

Welcome to the Skill Tagger application, the result of the paper "Des compétences aux savoir-faire : proposition d’extensions au métamodèle de COMPER et à MOODLE". You can read more about the work on this direction: https://eiah2025.sciencesconf.org/

## Overview

The Skill Tagger application it can be use to put the COMPER competencies model in practice alongside a moodle site environment.
As established in the paper this work can be divided into 2 parts that work together, a moodle server and a Graphology server. For installing instructions refer to the Section [Installation](#installation)
A set of skills can be found in the repository and will copy automatically to your moodle DB. But you can add your own set of skills.

## Table of contents

- [Overview](#overview)
- [Features](#features)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Example](#example)
- [FAQ](#faq)

## Features

1. **cosy_ct**: This folder contains the code needed to install the **COSY** moodle plugin of type block into your moodle site. Remember to zip the code before installing.
2. **executable**: This folder contains the executables needed to start a Graphology + SigmaVueJs server in your computer or in your server.
3. **config.js**: Contains the configuration for the server previously mentioned.

## Installation

- Download the repository in your local computer.
- Zip the content of the cosy_ct folder and install the plugin in your moodle site. You can find additional instructions in the moodle pages (https://docs.moodle.org/405/en/Installing_plugins)
- Copy the necessary file for the graphology server from the executable folder depending on which OS you want to install it.
- In the same location as the executable is copy the **config.js** file.

## Configuration

- Graphology server, at the **config.js** file  change the following information:
  1. The direction of the db of moodle (localhost)
  2. The user admin of the db of moodle (root)
  3. The password of the previous user (bitnami)
  4. The name of the db of moodle (bitnami_moodlebitnami_moodle)
  5. The port in which you want to start the Graphology server.
- In your Moodle site, access to the site with your admin credentials. You will need it to configure the general settings of the block plugin across all courses in Moodle.
  1. Change the "URL d'affichage du graphe" with the server direction and port where the Graphology+Vue service is deployed.

## Usage

At your moodle site, access to the course in which you want to begin to tag skills and create your own skills graphs.
1. Activate the "Edit mode" to access block edition.
2. Add a block of "COSY". This will add the COSY block to your course.
3. Click on the configuration of the block and change the section "Where this block appears" with the following settings:
   - Display on page types -> **Any Page**
   - This setting will allow to the block to be displayed in all activities of your course, *searching for taggeable elements*.
4. Access a page with content in your course. The block will automatically search for taggeable elements.
5. With the select tool at the COSY block select the tag in your page, *this will resalt the tag in the page making the location visible*.
6. You can create a edit this tag, changing the name or adding a recomandation object. Or ...
7. You can test the tag of how the element will appear to students.
8. At the end of the web page both in course and activity module you will see the graph of skills beign formed.
9. You can also edit this graph clicking on "Edition/vue simplifiée" on button "Edit". The server graphical interface presents their own buttons for alter the graph (creation or delete of nodes and relation links)

## FAQ

- **I can't install the moodle plugin, is there any solution?**
  - Remember to leave the code unchanged it, and *zip it* otherwise will not be readable to your Moodle site.
- **I have zip the files but it does not work yet**
  - This could be a version problem, we remind you that the Moodle necessary version is *3.9*. If your site is below this version contact your administrator and update Moodle.
  - If the Moodle version is the correct, be sure to have ziped not all the files of *cosy_ct* at the same level, instead zip the folder not the files. This behaviour could vary from Moodle version and installment.
- **How can I add a tag in my page?**
  - Edit the page and go to HTML mode, you will need to find any element and add an id to the html tag, this way:
  `<h5 id="title-h5"><br>Voici le h5 title ! </h5>`
  In this example the *title-h5* is the tag to be added to the page.
- **Do you support all activities of Moodle?**
  - The application supports: Page, Forum, Quiz, Book and additionally Pages made with the Reveal framework.
- **Which is the use-case recommended to use this application?**
  - The application will support all additions made both in course and activity level, but its recomended to update the graph in the course view in which you will have a more global view. And use the block in activity level to add recomendation objects that will fill up the information at the global level.
