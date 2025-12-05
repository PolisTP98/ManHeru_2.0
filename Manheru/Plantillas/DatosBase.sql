
/*
----------------------------------
	INSERTAR DATOS BASE
----------------------------------
*/

DECLARE @ID_Rol INT;

EXEC dbo.SP_AgregarRol
    @Nombre = 'Administrador',     
    @Estatus = 1,
    @ID_Rol = @ID_Rol OUTPUT;
Go



DECLARE @ID_Usuario INT;

EXEC dbo.SP_AgregarUsuario
    @Nombre = 'Ian',
    @Gmail = 'Ian@gmail.com',
    @Telefono = '5512345678',
    @ID_Rol = 1,        
    @Estatus = 1,
    @ID_Usuario = @ID_Usuario OUTPUT;
Go

