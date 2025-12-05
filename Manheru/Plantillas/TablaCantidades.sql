
/*
	Plantilla Tabla Cantidades
*/

IF NOT EXISTS (SELECT 1 FROM sys.tables WHERE name = 'Cantidades' AND schema_id = SCHEMA_ID('dbo'))
BEGIN
    CREATE TABLE dbo.Cantidades(
        ID_Cantidad INT IDENTITY(1,1) PRIMARY KEY,
        Cantidad INT NOT NULL,               
        Descripcion VARCHAR(50) NULL,        
        Estatus INT NOT NULL DEFAULT 1,      
        FechaCreacion DATETIME DEFAULT GETDATE()
    );
END
GO
	