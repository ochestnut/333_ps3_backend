# 333_ps3_backend

Owen Chestunut and Kyra Fox
Restructuring the backend created in ps2 to be complaint with MVC
In this repo, we have taken the features from the previous assignment and created a rest api controller in an mvc structure

Index is where the rerouting happens and can be thought of as the basecontroller 
  It parses the url and reroutes the user to a specific method in the child controllers
  
SongController and UserController are our controllers, which take info from the index and reroute from the controller to the model for database changes

SongModel and UserModel are the models that connect to the ratings and users tables, respectively, of the database.
  They perform the logic to determine whether a song should or should not be added based on repitition, user login stats, etc.

Working well in postman and sending accurate json responses with different requests.

Was connecting well to the frontend until things got messed up in the frontend repo with changes uncommitted and such; was forced to revert to older version but hopefully you can see the version history and see where we were able to connect frontend to backend
