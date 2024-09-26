# Block cosy_ct Bayonne 

This is a plugin that was created with the purpose to serve to the
recommendation system on e-learning platforms described in the research by Roux et al.
You can find more about the work here: https://doi.org/10.1007/978-3-031-18512-0_5

### Version

The actual mature version is **2.1**.

### Features

v1.0

- Added the following supported resources: 
  1. Page.
  2. Reveal Page.
- Added funcionality that detect all the course modules and sections to use them as a containers.
- Added functionality to detect anchors in the supported resources.
- Added functionality to resalt the text of the current anchor selected in the view source.
- Added functionality to redirect to new tab the current anchor selected.
- Added functionality to change the name of the current anchor selected.
- Added tables: containers, anchors.
- Roles with permission to view the block: manager, coursecreator, teacher, editingteacher.

---

v2.0

- Added the following supported resources: 
  1. Book.
  2. Chapters of book.
  3. Quiz.
  4. Forum.
- Changed setting of block to only view in **editing mode**. Hided in normal mode.
- Added functionality to create Recomendation Objects.
- Added functionality to edit Recomendation Objects.
- Added functionality to delete Recomendation Objects.
- Added functionality to create Competencies Associations.
- Added functionality to delete Competencies Associations.
- Added functionality to edit the weight of Competencies Associations.
- Added functionality to populate initial data to new tables in Moodle DB once installed.
- Added services to make Ajax Calls, can also be used to REST calls. If you want to do this please review documentation of Moodle on how to use external API calls. (https://moodledev.io/docs/apis/subsystems/external):
  1. change_anchor_name
  2. create_rec_object
  3. create_comp_ass
  4. edit_rec_object
  5. delete_rec_object
  6. delete_comp_association
  7. get_compasses
  8. minus_weight_compass
  9. plus_weight_compass
  10. change_weight_compass
- Added tables: typesrecobj, approaches, categories, medias, recobj, recobj_medias, recobj_knowho

---

v2.1
- Removed alert box of alerts 'Are you sure you want to leave this site?' in windows that were not neccesarly. ie: to view anchor detail.
- Changed initial text of block when inserted in a course page has been.
- Removed field of 'algorithm' in table containers, since it wasnt been used.
- Added fields of 'display_name' in the following tables: typesrecobj, approaches, categories, medias.

### How to use

1. Install the plugin into your moodle. You need to have administrator privileges for this step.
2. Add the block with the name 'IUT Tag block' to the course you wish. You need to have the 'Editing mode' on in order to view the block.
3. In the settings of the block go to **Display on page types** and select the option **Any page**. This will allow the block to appear in other pages like pages, books, etc.
4. At this time not all resources are supported in the plugin, lets review the ones that are.

#### Page

In the Resource Page, the funcionality is simple:
1. Go to the 'Edit settings' of the page.
2. Go to section 'Content' and enable the 'HTML' mode to view the html code which is rendered in order to show the page.
3. Select the text that you wish to make an anchor and marked with the id of your choice. Like the example right above:

    <span id="Disposition_de_la_barre_d'outils_de_l'éditeur_Atto">Disposition de la barre d'outils de l'éditeur Atto</span>

5. Once you have marked all the anchors, click on button 'Save and Display'.
6. In the page view you can now review the anchor that was created on the 'IUT Tag block'.
7. You can review in detail this anchor with the button 'Edit IUT anchor', add a recommendation object to this or change its name.
8. You can also review on where this anchor takes you with the button 'Test IUT anchor'.

#### Forum

In the Resource Forum the functionality is the same as the Page Resource.
But anchors can only be marked in the instructions of the forum and not to a specific response.

#### Quiz

In the Resource Forum the functionality is the same as the Page Resource.
But anchors can only be marked in the instructions of the quiz and not to a specific question.

#### Book

In the Resource Forum the functionality is the same as the Page Resource.
With the difference that the plugin treat Book chapters as different containers, they belong to the same book, but the anchors will change when selecting different chapters.

#### Page Reveal

The Page Reveal Resource is almost identical to the page resource. But in order to tell the plugin that you wish to treat this page as a Page Reveal Resource, please include the following tag in the html code of the page:

    <div id="revealsrc"></div>
    
This will tell the plugin that it has to review not the anchors of the Moodle page but the anchors that you have marked in your Reveal Resource.


### Bibliography

Roux, L., Nodenot, T., Etcheverry, P., Dagorret, P., Marquesuzaa, C., Lopisteguy, P. (2023). A Learner’s Behavior Model for an E-Learning Hybrid Recommender System. In: Ifenthaler, D., Sampson, D.G., Isaías, P. (eds) Open and Inclusive Educational Practice in the Digital World. Cognition and Exploratory Learning in the Digital Age. Springer, Cham. https://doi.org/10.1007/978-3-031-18512-0_5
