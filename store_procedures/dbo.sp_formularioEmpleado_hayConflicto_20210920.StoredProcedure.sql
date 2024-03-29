USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_formularioEmpleado_hayConflicto_20210920]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- =============================================
-- Autor: Haydelis Hernandez 
-- Creado el: 25/03/2019
-- Descripcion: Obtiene las variables diponibles de un documento subido por carga masiva 
-- Modificado: gdiaz 12/04/2021
-- Ejemplo:exec [sp_formularioEmpleado_hayConflicto] 
-- =============================================
CREATE PROCEDURE [dbo].[sp_formularioEmpleado_hayConflicto_20210920]
	@empleadoid VARCHAR(10),
    @idDocumento integer,
	@rut VARCHAR(10)
AS
BEGIN
	SET NOCOUNT ON;

    DECLARE @empleadoFormularioid INT
    DECLARE @idFormulario INT
    DECLARE @estadoFormularioid INT
    DECLARE @fechaModificacion DATETIME
    DECLARE @actorid INT
    /*
    DECLARE @estadoFormularioid INT*/
    DECLARE @lmensaje VARCHAR(100)
	DECLARE @error INT;
    
    BEGIN TRANSACTION 
	    BEGIN TRY
             
            SET @fechaModificacion = GETDATE()
            /*IF (@accion = 'FIRMA_FORMULARIO')
                BEGIN*/
                    IF EXISTS ( SELECT empleadoFormularioid FROM empleadoFormulario WHERE empleadoid = @empleadoid AND idDocumento = @idDocumento AND estadoFormularioid IN (2000) )
                        BEGIN
                            SELECT 
                                @empleadoFormularioid = empleadoFormularioid,
                                @idFormulario = idFormulario
                            FROM empleadoFormulario WHERE empleadoid = @empleadoid AND idDocumento = @idDocumento AND estadoFormularioid IN (2000)

                            INSERT INTO EnvioCorreos (CodCorreo,documentoid,RutUsuario,TipoCorreo) 
                            VALUES (
                                12, @empleadoFormularioid, @rut, 3
                            )
                        END
                --END
            COMMIT TRANSACTION
	    END TRY

	    BEGIN CATCH
	        ROLLBACK TRANSACTION 
		
            SET @error		= ERROR_NUMBER()
            SET @lmensaje	= ERROR_MESSAGE()
			
	    END CATCH
	
	SELECT @error AS error, @lmensaje AS mensaje
END
GO
