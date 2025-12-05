
/*
	Plantilla Tabla Usuarios 
*/

IF NOT EXISTS (SELECT 1 FROM sys.tables WHERE name = 'Usuarios' AND schema_id = SCHEMA_ID('dbo'))
BEGIN
    CREATE TABLE dbo.Usuarios(
	ID_Usuario Int IDENTITY(1,1) PRIMARY KEY,
	Nombre Varchar (100) not null,
	Gmail Nvarchar (255) not null,
	Telefono Nvarchar (20) not null,
	Estatus Bit,
	ID_Rol Int,
	ID_Direccion Int null,
	Foreign Key (ID_Direccion) References Direcciones (ID_Direccion),
	Foreign Key (ID_Rol) References Roles(ID_Rol)
    );
END
GO