# Api-Ingeniat

-Importar el archivo SQL 

-copiar la carpeta ProyectoIngeniatAldoRamirez a htdocs para poder consumir la api. 

-las pruebas se realizaron en Postman!.

- La ruta es : http://127.0.0.1/ProyectoIngeniatAldoRamirez/controller/APIPublicaciones.php


  REQUEST GET: USUARIO DEFAULT CARGADO EN LA BD PARA PRUEBAS { "Correo":"administrador@correo.com", "Password":"Admin", "accion":"Login" }
  
  RESPONSE: {"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE2MzMzMDMxODEsImF1ZCI6IjMzMmNhODc1MDY3ZGNmZDRiNjg3YmM3OTUwOTlhZWVjNDEzMTIwZWQiLCJkYXRhIjp7IklkVXNlciI6bnVsbCwiTm9tYnJlIjpudWxsLCJSb2wiOm51bGx9fQ.Smo9CbbG7eUElxn1imgCN6WmyMtkttL4kPUtbin7ZcM"}
  
  REQUEST POST: { "Nombre":"Aldo", "Apellido":"Ramirez", "Correo":"aldoRamirez@correo.com", "Password":"Ramirez", "Rol":"5", "accion":"registroDeUsuario" }
  
  RESPONSE: {"nCodigo":0,"sMensaje":"Usuario registrado exitosamente"}
  
  REQUEST POST: { "Titulo":"Mi primer Publicacion", "Descripcion":"Esta es mi primer publicacion", "accion":"creacionDePublicacion" }
  
  RESPONSE: {"Codigo":0,"Mensaje":"Publicaci\u00f3n registrada","IdPublicacion":1}
  
  REQUEST PUT:  { "IdPublicacion": 1, "Titulo": "Publicacion Editada", "Descripcion": "Descripdion editada", "accion": "actualizacionDePublicacion" }
  
  RESPONSE: {"Codigo":0,"Mensaje":"Publiaci\u00f3n actualizada","IdPublicacion":1}
  
  REQUEST DELETE: { "IdPublicacion": 1, "accion": "eliminacionDePublicacionLogica" }
  
  RESPONSE: {"Codigo":0,"Mensaje":"Publiaci\u00f3n elimnada","IdPublicacion":1}
  
  REQUEST GET:  { "accion": "consultaDePublicaciones" } 
  
  RESPONSE: {"Titulo":"Publicacion Editada","Descripcion":"Descripdion editada","FecRegistro":"2021-10-03 17:35:27","Autor de publicacion":"Admistrador.","Rol":"Rol alto"}
