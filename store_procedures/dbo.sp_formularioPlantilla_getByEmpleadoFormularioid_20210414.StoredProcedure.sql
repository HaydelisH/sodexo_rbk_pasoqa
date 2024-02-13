USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_formularioPlantilla_getByEmpleadoFormularioid_20210414]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- =============================================
-- Autor: Haydelis Hernandez 
-- Creado el: 25/03/2019
-- Descripcion: Obtiene las variables diponibles de un documento subido por carga masiva 
-- Ejemplo:exec [sp_formularioPlantilla_getByEmpleadoFormularioid] 
-- =============================================
CREATE PROCEDURE [dbo].[sp_formularioPlantilla_getByEmpleadoFormularioid_20210414]
	@empleadoFormularioid INT
AS
BEGIN	
	SET NOCOUNT ON;			
 
	SELECT 
        formularioPlantilla.idFormulario
        --,formularioPlantilla.revisaOrigenEstructuraData
    FROM 
        formularioPlantilla
    INNER JOIN empleadoFormulario ON empleadoFormulario.idFormulario = formularioPlantilla.idFormulario
    WHERE 
        empleadoFormulario.empleadoFormularioid = @empleadoFormularioid
	
END
GO
