
/*
	Plantilla Tabla Tipos de producto
*/

IF NOT EXISTS (SELECT 1 FROM sys.tables WHERE name = 'Tipos' AND schema_id = SCHEMA_ID('dbo'))
BEGIN
    CREATE TABLE dbo.Tipos(
	ID_Tipo Int IDENTITY(1,1) PRIMARY KEY,
	Nombre Varchar (100) not null,
	Estatus Bit
    );
END
GO