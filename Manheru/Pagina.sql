
/*
=======================
	  Pagina Web
=======================
*/

IF NOT EXISTS (SELECT 1 FROM sys.databases WHERE name = 'Pagina')
BEGIN
	CREATE DATABASE Pagina;
END
Go

Drop Database Pagina;

Use Pagina;
Go

--- Plantilla Tipos
:r "C:\Users\ianrd\OneDrive\Documentos\BD\Manheru\Plantillas\TablaTipos.sql"

--- Plantilla Productos
:r "C:\Users\ianrd\OneDrive\Documentos\BD\Manheru\Plantillas\TablaProductos.sql"

--- Plantilla Roles
:r "C:\Users\ianrd\OneDrive\Documentos\BD\Manheru\Plantillas\TablaRoles.sql"

-- Plantilla Pagina
:r "C:\Users\ianrd\OneDrive\Documentos\BD\Manheru\Plantillas\TablasPagina.sql"

--- Plantilla Usuarios
:r "C:\Users\ianrd\OneDrive\Documentos\BD\Manheru\Plantillas\TablaUsuarios.sql"

-- Plantilla Cantidades
:r "C:\Users\ianrd\OneDrive\Documentos\BD\Manheru\Plantillas\TablaCantidades.sql"

--- Plantilla Ventas
:r "C:\Users\ianrd\OneDrive\Documentos\BD\Manheru\Plantillas\TablaVentas.sql"

--- Errores
:r "C:\Users\ianrd\OneDrive\Documentos\BD\Manheru\Errores\EstructuraDeErrores.sql"
:r "C:\Users\ianrd\OneDrive\Documentos\BD\Manheru\Errores\DatosDeErrores.sql"

-- Mensajes de error
:r "C:\Users\ianrd\OneDrive\Documentos\BD\Manheru\Funcionamiento\Procedimientos almacenados\MensajesDeError.sql"

--- Insertar SP
:r "C:\Users\ianrd\OneDrive\Documentos\BD\Manheru\Funcionamiento\Procedimientos almacenados\InsertarRegistro.sql"

--- Datos Base
:r "C:\Users\ianrd\OneDrive\Documentos\BD\Manheru\Plantillas\DatosBase.sql"