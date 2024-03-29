USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_formularioPlantilla_carga_20210414]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- =============================================
-- Autor: Haydelis Hernandez 
-- Creado el: 25/03/2019
-- Descripcion: Obtiene las variables diponibles de un documento subido por carga masiva 
-- Ejemplo:exec [sp_formularioPlantilla_carga] 
-- =============================================
CREATE PROCEDURE [dbo].[sp_formularioPlantilla_carga_20210414]
	@Rut VARCHAR(10),
    @idFormulario integer,
	@usuarioid VARCHAR(10)
AS
BEGIN
	SET NOCOUNT ON;

    DECLARE @empleadoFormularioid INT
    DECLARE @fechaCarga DATETIME
    DECLARE @actorid INT
    DECLARE @estadoFormularioid INT
    DECLARE @lmensaje VARCHAR(100)
	DECLARE @error INT;
    
    BEGIN TRANSACTION 
	    BEGIN TRY
            SET @fechaCarga = GETDATE()
            SET @estadoFormularioid = 1
            SET @actorid = 1
            IF EXISTS ( SELECT empleadoFormularioid FROM empleadoFormulario WHERE empleadoid = @Rut AND idFormulario = @idFormulario AND estadoFormularioid IN (1) )
                BEGIN
                    set @lmensaje = 'Ya existe una asignacion de este formulario para el rut'
                    set @error = 1
                END
            ELSE
                BEGIN
                    INSERT INTO empleadoFormulario (
                        empleadoid, 
                        idFormulario, 
                        fechaCarga, 
                        estadoFormularioid
                    ) VALUES (
                        @Rut,
                        @idFormulario,
                        @fechaCarga,
                        @estadoFormularioid
                    )
                    
                    SELECT @empleadoFormularioid = scope_identity()

                    INSERT INTO datosFormulario (
                        empleadoFormularioid,
                        actorid,
                        fecha,
                        estadoFormularioid,
                        usuarioid
                    )
                    VALUES (
                        @empleadoFormularioid,
                        @actorid,
                        @fechaCarga,
                        @estadoFormularioid,
                        @usuarioid
                    )
                    INSERT INTO EnvioCorreos (CodCorreo,documentoid,RutUsuario,TipoCorreo) 
                    VALUES (
                        11, @empleadoFormularioid, @Rut, 2
                    )
                END
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
