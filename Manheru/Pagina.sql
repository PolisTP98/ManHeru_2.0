
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
:r "D:\GitHub\ManHeru_2.0\Manheru\Plantillas\TablaTipos.sql"

--- Plantilla Productos
:r "D:\GitHub\ManHeru_2.0\Manheru\Plantillas\TablaProductos.sql"

--- Plantilla Roles
:r "D:\GitHub\ManHeru_2.0\Manheru\Plantillas\TablaRoles.sql"

-- Plantilla Pagina
:r "D:\GitHub\ManHeru_2.0\Manheru\Plantillas\TablasPagina.sql"

--- Plantilla Usuarios
:r "D:\GitHub\ManHeru_2.0\Manheru\Plantillas\TablaUsuarios.sql"

-- Plantilla Cantidades
:r "D:\GitHub\ManHeru_2.0\Manheru\Plantillas\TablaCantidades.sql"

--- Plantilla Ventas
:r "D:\GitHub\ManHeru_2.0\Manheru\Plantillas\TablaVentas.sql"

--- Errores
:r "D:\GitHub\ManHeru_2.0\Manheru\Errores\EstructuraDeErrores.sql"
:r "D:\GitHub\ManHeru_2.0\Manheru\Errores\DatosDeErrores.sql"

-- Mensajes de error
:r "D:\GitHub\ManHeru_2.0\Manheru\Funcionamiento\Procedimientos almacenados\MensajesDeError.sql"

--- Insertar SP
:r "D:\GitHub\ManHeru_2.0\Manheru\Funcionamiento\Procedimientos almacenados\InsertarRegistro.sql"

--- Datos Base
:r "D:\GitHub\ManHeru_2.0\Manheru\Plantillas\DatosBase.sql"