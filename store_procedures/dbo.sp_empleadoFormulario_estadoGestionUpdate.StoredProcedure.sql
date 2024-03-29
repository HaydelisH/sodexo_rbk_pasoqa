USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_empleadoFormulario_estadoGestionUpdate]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez 
-- Creado el: 25/03/2019
-- Descripcion: Obtiene las variables diponibles de un documento subido por carga masiva 
-- Ejemplo:exec [sp_empleadoFormulario_estadoGestionUpdate] 
-- =============================================
CREATE PROCEDURE [dbo].[sp_empleadoFormulario_estadoGestionUpdate]
	@estadoFormularioid integer,
    @Observacion VARCHAR(200),
	@empleadoFormularioid integer,
	@usuarioid VARCHAR(10),
	@actorid integer
AS
BEGIN
	SET NOCOUNT ON;

    /*
    DECLARE @empleadoFormularioid INT
    DECLARE @idFormulario INT
    DECLARE @estadoFormularioid INT
    DECLARE @actorid INT
    */
    DECLARE @fechaModificacion DATETIME
    DECLARE @lmensaje VARCHAR(100)
	DECLARE @error INT;
    
    BEGIN TRANSACTION 
	    BEGIN TRY
             
            SET @fechaModificacion = GETDATE()
            /*
            SET @estadoFormularioid = 1
            SET @actorid = 1*/
            --IF (@accion = 'FIRMA_FORMULARIO')
                --BEGIN
                    --IF EXISTS ( SELECT empleadoFormularioid FROM empleadoFormulario WHERE empleadoid = @empleadoid AND idDocumento = @idDocumento AND estadoFormularioid IN (1) )
                        --BEGIN
                            /*SELECT 
                                @empleadoFormularioid = empleadoFormularioid,
                                @idFormulario = idFormulario
                            FROM empleadoFormulario WHERE empleadoid = @empleadoid AND idDocumento = @idDocumento AND estadoFormularioid IN (1)
                            IF (@idFormulario = 1)
                                BEGIN*/
                                    /*IF EXISTS ( SELECT ContratoDatosVariables.idDocumento FROM ContratoDatosVariables 
                                                INNER JOIN Contratos ON Contratos.idDocumento = ContratoDatosVariables.idDocumento 
                                                WHERE 
                                                Contratos.idEstado = 6
                                                AND
                                                ContratoDatosVariables.idDocumento = @idDocumento
                                                AND (
                                                    ContratoDatosVariables.querySiNoObs1 = 'si'
                                                    OR
                                                    ContratoDatosVariables.querySiNoObs2 = 'si'
                                                    OR
                                                    ContratoDatosVariables.querySiNoObs3 = 'si'
                                                    OR
                                                    ContratoDatosVariables.querySiNoObs4 = 'si'
                                                    OR
                                                    ContratoDatosVariables.querySiNoObs5 = 'si'
                                                    OR
                                                    ContratoDatosVariables.querySiNoDinamico1 = 'si'
                                                )
                                        )
                                        BEGIN
                                            SET @estadoFormularioid = 2 -- Requiere revision RRHH
                                            SET @actorid = 1
                                        END
                                    ELSE
                                        BEGIN*/
                                            --SET @estadoFormularioid = 6 -- Cerrado sin conflicto
                                            --SET @actorid = 1
                                        --END
                IF (@estadoFormularioid = 3 or @estadoFormularioid = 5) --Cerrado por RRHH || Cerrado Comite de etica
                    BEGIN
                        UPDATE empleadoFormulario SET estadoFormularioid = @estadoFormularioid WHERE empleadoFormularioid = @empleadoFormularioid
                        INSERT INTO datosFormulario (empleadoFormularioid, actorid, fecha, estadoFormularioid, usuarioid, observacion) VALUES (@empleadoFormularioid, @actorid, @fechaModificacion, @estadoFormularioid, @usuarioid, @Observacion)
                    END
                ELSE
                    BEGIN
                        UPDATE empleadoFormulario SET estadoFormularioid = @estadoFormularioid WHERE empleadoFormularioid = @empleadoFormularioid
                        INSERT INTO datosFormulario (empleadoFormularioid, actorid, fecha, estadoFormularioid, usuarioid) VALUES (@empleadoFormularioid, @actorid, @fechaModificacion, @estadoFormularioid, @usuarioid)
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
