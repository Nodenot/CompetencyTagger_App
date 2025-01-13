# Skill Tagger Application
Welcome to the Skill Tagger application, the result of the paper "Des compétences aux savoir-faire : proposition d’extensions au métamodèle de COMPER et à MOODLE". You can read more about the work on this direction: https://eiah2025.sciencesconf.org/
As established in the paper this work can be divided into 2 parts that work together, a moodle server and a Graphology server. For installing instructions refer to the Section *"How does it work?"*
A set of skills can be found in the repository and will copy automatically to your moodle DB. But you can add your own set of skills.
## Content of this repository
1. *cosy_ct* : This folder contains the code needed to install the **COSY** moodle plugin of type block into your moodle site. Remember to zip the code before installing.
2. *executable* : This folder contains the executables needed to start a Graphology + SigmaVueJs server in your computer or in your server.
3. *config.js* : Contains the configuration for the server previously mentioned.
## How does it work ?
- Download the repository in your local computer.
- Zip the content of the cosy_ct folder and install the plugin in your moodle site. You can find additional instructions in the moodle pages (https://docs.moodle.org/405/en/Installing_plugins)
- Copy the necessary file for the graphology server from the executable folder depending on which OS you want to install it.
- In the same location as the executable is copy the **config.js** file.
- Change the configuration to your own configuration, you will need:
  1. The direction of the db of moodle (localhost)
  2. The user admin of the db of moodle (root)
  3. The password of the previous user (bitnami)
  4. The name of the db of moodle (bitnami_moodlebitnami_moodle)
  5. The port in which you want to serve the Graphology server.
## FAQ
- ccc
- bb
- a
