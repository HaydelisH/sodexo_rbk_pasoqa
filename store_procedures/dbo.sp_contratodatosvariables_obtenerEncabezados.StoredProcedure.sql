USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_contratodatosvariables_obtenerEncabezados]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez 
-- Creado el: 25/03/2019
-- Descripcion: Obtiene los datos variables de un documento subido por carga masiva 
-- Ejemplo:exec [sp_contratodatosvariables_obtener] 
-- =============================================
CREATE PROCEDURE [dbo].[sp_contratodatosvariables_obtenerEncabezados]

AS
BEGIN	
	SET NOCOUNT ON;			
 
	SELECT TOP 1
		 cc.ciudad As CiudadLocal
		,cc.comuna As ComunaLocal
		,cc.direccion As DireccionLocal
		,cc.nombrecentrocosto As NombreLocal
		,CONVERT(CHAR(10),CV.FechaInicio ,105) As FechaInicio
		,CONVERT(CHAR(10),CV.FechaInicio ,105) As FechaInicioContrato
		,CONVERT(CHAR(10),CV.FechaRetorno ,105) As FechaRetorno
		,CONVERT(CHAR(10),CV.FechaTermino ,105) As FechaTermino
		,CONVERT(CHAR(10),CV.FechaTermino ,105) As FechaTermino1
		,CONVERT(CHAR(10),CV.FechaTermino2 ,105) As FechaTermino2
		,CV.AreaTrabajo
		,CV.AsignacionCajaFija
		,CV.AsignacionColacion
		,CV.AsignacionPerdidaCajaPorcentaje
		,CV.AsignacionPerdidaCajaValor
		,CV.Cargo
		,CV.Cargo As CodCargo
		,CE.Descripcion As DescripcionCargo
		,CE.Titulo As TituloCargo
		,CE.Obligaciones As ObligacionesCargo
		,cc.ciudad As CiudadFirma
		,cc.ciudad As CiudadFirmaContrato
		,CV.ClaseContrato
		,CV.CentroCosto As CentroCosto
		,CV.CentroCosto As CodDivPersonal
		,CV.DescCargoRiohs
		,CV.Jornada
		,CV.Jornada As CodJornada
		,CV.Movilizacion
		,CV.Movilizacion As AsignacionMovilizacion
		,CV.Posicion
		,CV.SueldoBase
		,CV.Texto1
		,CV.TipoMovimiento
		,CV.Bono
		,CV.BonoResponsabilidad
		,CV.Anno As Año
		,CV.Anno As Anno
		,CV.Numero
		,CV.Celular
  FROM 
	ContratoDatosVariables CV
  INNER JOIN Contratos C ON C.idDocumento = CV.idDocumento
  INNER JOIN centroscosto cc ON CV.CentroCosto = cc.centrocostoid
  LEFT JOIN CargosEmpleado CE ON CV.Cargo = CE.idCargoEmpleado
	
END
GO
