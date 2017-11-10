# API Routes

### `GET` [/](http://localhost/ims-api/)
##### core.controller:root
###### root

### `OPTIONS` [/{routes:.+}](http://localhost/ims-api/{routes:.+})

### `POST` [/student/send/convention](http://localhost/ims-api/student/send/convention)
##### ims.convention.controller:submit
###### student.convention.send

### `POST` [/admin/login](http://localhost/ims-api/admin/login)
##### security.auth.controller:login
###### login

### `POST` [/admin/auth/refresh](http://localhost/ims-api/admin/auth/refresh)
##### security.auth.controller:refresh
###### jwt.refresh

### `GET` [/admin/users/me](http://localhost/ims-api/admin/users/me)
##### security.auth.controller:me
###### users.me

