USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_formularioPlantilla_existe_20210414]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- =============================================
-- Autor: Haydelis Hernandez 
-- Creado el: 25/03/2019
-- Descripcion: Obtiene las variables diponibles de un documento subido por carga masiva 
-- Ejemplo:exec [sp_formularioPlantilla_existe] 
-- =============================================
CREATE PROCEDURE [dbo].[sp_formularioPlantilla_existe_20210414]
	@Rut VARCHAR(10),
    @idFormulario integer
AS
BEGIN
	SET NOCOUNT ON;

    DECLARE @lmensaje VARCHAR(100)
	DECLARE @error INT;
    
    IF EXISTS ( SELECT empleadoFormularioid FROM empleadoFormulario WHERE empleadoid = @Rut AND idFormulario = @idFormulario AND estadoFormularioid IN (1) )
        BEGIN
            set @lmensaje = 'Ya existe una asignacion de este formulario para el rut'
            set @error = 1
        END
    IF NOT EXISTS ( SELECT TOP 1 idDocumento FROM contratoDatosVariables WHERE Rut = @Rut ORDER BY idDocumento DESC )
        BEGIN
            set @lmensaje = 'El trabajador no existe en el sistema'
            set @error = 1
        END

	SELECT @error AS error, @lmensaje AS mensaje 
END
GO
