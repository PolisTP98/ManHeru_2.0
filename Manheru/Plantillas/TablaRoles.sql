
/*
	Plantilla Tabla Usuarios 
*/

IF NOT EXISTS (SELECT 1 FROM sys.tables WHERE name = 'Roles' AND schema_id = SCHEMA_ID('dbo'))
BEGIN
    CREATE TABLE dbo.Roles(
	ID_Rol Int IDENTITY(1,1) PRIMARY KEY,
	Nombre Varchar (100) not null,
	Estatus Bit
    );
END
GO