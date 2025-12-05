/*
===================================================================================================================================
                                             INSERTAR DATOS EN EL CATALOGO DE ERRORES (IDEMPOTENTE)
===================================================================================================================================
*/

-- (1) Insertar datos en el catalogo "CodigosDeErrores" solo si no existen previamente

IF NOT EXISTS (SELECT 1 FROM dbo.CodigosDeErrores WHERE CodigoDeError = 50000)
    INSERT INTO dbo.CodigosDeErrores (CodigoDeError, Mensaje)
    VALUES (50000, 'Ocurrio un error durante la ejecucion del procedimiento almacenado..');

IF NOT EXISTS (SELECT 1 FROM dbo.CodigosDeErrores WHERE CodigoDeError = 50100)
    INSERT INTO dbo.CodigosDeErrores (CodigoDeError, Mensaje)
    VALUES (50100, 'Los siguientes campos no aceptan valores NULL: ');

IF NOT EXISTS (SELECT 1 FROM dbo.CodigosDeErrores WHERE CodigoDeError = 50200)
    INSERT INTO dbo.CodigosDeErrores (CodigoDeError, Mensaje)
    VALUES (50200, 'Registro duplicado en la base de datos, cancelando insercion..');

IF NOT EXISTS (SELECT 1 FROM dbo.CodigosDeErrores WHERE CodigoDeError = 50300)
    INSERT INTO dbo.CodigosDeErrores (CodigoDeError, Mensaje)
    VALUES (50300, 'Registro duplicado en la base de datos, cancelando actualizacion..');

IF NOT EXISTS (SELECT 1 FROM dbo.CodigosDeErrores WHERE CodigoDeError = 50400)
    INSERT INTO dbo.CodigosDeErrores (CodigoDeError, Mensaje)
    VALUES (50400, 'No se pudo eliminar el registro, intentelo de nuevo mas tarde');

IF NOT EXISTS (SELECT 1 FROM dbo.CodigosDeErrores WHERE CodigoDeError = 50500)
    INSERT INTO dbo.CodigosDeErrores (CodigoDeError, Mensaje)
    VALUES (50500, 'El ID insertado no hace referencia a ningun registro existente: ');

IF NOT EXISTS (SELECT 1 FROM dbo.CodigosDeErrores WHERE CodigoDeError = 50600)
    INSERT INTO dbo.CodigosDeErrores (CodigoDeError, Mensaje)
    VALUES (50600, 'El valor del campo es invalido: ');
