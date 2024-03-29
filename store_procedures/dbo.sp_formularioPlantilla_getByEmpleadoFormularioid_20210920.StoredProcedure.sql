USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_formularioPlantilla_getByEmpleadoFormularioid_20210920]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- =============================================
-- Autor: Haydelis Hernandez 
-- Creado el: 25/03/2019
-- Descripcion: Obtiene las variables diponibles de un documento subido por carga masiva 
-- Modificado: gdiaz 11/04/2021
-- Ejemplo:exec [sp_formularioPlantilla_getByEmpleadoFormularioid] 
-- =============================================
CREATE PROCEDURE [dbo].[sp_formularioPlantilla_getByEmpleadoFormularioid_20210920]
	@empleadoFormularioid INT
AS
BEGIN	
	SET NOCOUNT ON;			
 
	SELECT 
        formularioPlantilla.revisaOrigenEstructuraData,
        formularioPlantilla.idPlantilla,
        formularioPlantilla.idFormulario,
        FormularioDatosVariables.lugarpagoid,
        FormularioDatosVariables.centrocostoid,
        FormularioDatosVariables.empresaid,
        FormularioDatosVariables.CiudadFirma,
		CONVERT(CHAR(10),FormularioDatosVariables.FechaDocumento ,105) As FechaDocumento,
        CONVERT(CHAR(10),FormularioDatosVariables.FechaIngreso ,105) As FechaIngreso,
        FormularioDatosVariables.Cargo,
        FormularioDatosVariables.FirmantesJSON
    FROM 
        formularioPlantilla
    INNER JOIN empleadoFormulario ON empleadoFormulario.idFormulario = formularioPlantilla.idFormulario
    INNER JOIN FormularioDatosVariables ON FormularioDatosVariables.empleadoFormularioid = empleadoFormulario.empleadoFormularioid
    WHERE 
        empleadoFormulario.empleadoFormularioid = @empleadoFormularioid
END
GO
