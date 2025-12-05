
/*
	Plantilla Tabla Productos
*/

IF NOT EXISTS (SELECT 1 FROM sys.tables WHERE name = 'Productos' AND schema_id = SCHEMA_ID('dbo'))
BEGIN
    CREATE TABLE dbo.Productos(
	ID_Producto Int IDENTITY(1,1) PRIMARY KEY,
	Nombre Varchar (100) not null,
	Estatus Bit,
	ID_Tipo Int,
	Foreign Key (ID_Tipo) References Tipos(ID_Tipo)
    );
END
GO