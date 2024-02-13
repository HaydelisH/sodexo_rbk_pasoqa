USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_empleadoFormulario_getDatosVariables]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez 
-- Creado el: 25/03/2019
-- Descripcion: Obtiene las variables diponibles de un documento subido por carga masiva 
-- Modificado: gdiaz 11/04/2021
-- Ejemplo:exec [sp_empleadoFormulario_getDatosVariables] 
-- =============================================
--NUEVO
create PROCEDURE [dbo].[sp_empleadoFormulario_getDatosVariables]
	@empleadoFormularioid INT
AS
BEGIN	
	SET NOCOUNT ON;			
 
	SELECT 
        FormularioPersonas.personaid
		,FormularioPersonas.nacionalidad
		,FormularioPersonas.nombre
		,FormularioPersonas.appaterno
		,FormularioPersonas.apmaterno
		,FormularioPersonas.correo
		,FormularioPersonas.direccion
		,FormularioPersonas.ciudad
		,FormularioPersonas.comuna
		,CONVERT(CHAR(8),FormularioPersonas.fechanacimiento  ,112)
		,FormularioPersonas.estadocivil
		,FormularioPersonas.rol
		,FormularioDatosVariables.empresaid
		,FormularioDatosVariables.centrocostoid
		,FormularioDatosVariables.lugarpagoid
        ,FormularioDatosVariables.CiudadFirma
        ,CONVERT(CHAR(8),FormularioDatosVariables.FechaDocumento  ,112) AS FechaDocumento
        ,CONVERT(CHAR(10),FormularioDatosVariables.FechaDocumento  ,105) AS FechaDocumento_formato
        ,CONVERT(CHAR(8),FormularioDatosVariables.FechaIngreso  ,112) AS FechaIngreso
        ,CONVERT(CHAR(10),FormularioDatosVariables.FechaIngreso  ,105) AS FechaIngreso_formato
        ,FormularioDatosVariables.Cargo
        ,FormularioDatosVariables.FirmantesJSON
    FROM 
        FormularioPersonas
    INNER JOIN FormularioDatosVariables ON FormularioDatosVariables.empleadoFormularioid = FormularioPersonas.empleadoFormularioid
    WHERE 
        FormularioDatosVariables.empleadoFormularioid = @empleadoFormularioid
	
END
GO
