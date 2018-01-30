# Lab task 2 for 0130

Lag en side hvor brukeren kan laste opp bilder. Bildene skal lagres på disk mens en thumbnailutgave av bildet skal lagres i databasen. Thumbnailbildet skaleres til 75x75 punkter. Vis en oversikt over alle bilder med thumbnailbildene og tittel på bildene. La brukeren laste ned det originale bildet.

## Note on running

* You need to add a directory `uploaded_images` and give apache the write-permissions to the directory.
* Keep in mind that the contents of the db needs to match the contents on your disk. If stuff isn't working: check that the ids in the db matches those on file.
