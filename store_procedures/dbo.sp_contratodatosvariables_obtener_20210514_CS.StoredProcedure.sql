USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_contratodatosvariables_obtener_20210514_CS]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- =============================================
-- Autor: Haydelis Hernandez 
-- Creado el: 25/03/2019
-- Descripcion: Obtiene las variables diponibles de un documento subido por carga masiva 
-- Modificado: gdiaz 11/04/2021
-- Ejemplo:exec [sp_contratodatosvariables_obtener] 
-- =============================================
CREATE PROCEDURE [dbo].[sp_contratodatosvariables_obtener_20210514_CS]
	@idDocumento INT
AS
BEGIN	
	SET NOCOUNT ON;			
 
	SELECT 
		 cc.ciudad As CiudadLocal
		,cc.comuna As ComunaLocal
		,cc.direccion As DireccionLocal
		,cc.nombrecentrocosto As NombreLocal
		,CONVERT(CHAR(10),CV.FechaInicio ,105) As FechaInicio
		--,CONVERT(CHAR(10),CV.FechaTermino ,105) As FechaTermino
		,CONVERT(CHAR(10),CV.FechaIngreso ,105) As FechaIngreso
		,CONVERT(CHAR(10),CV.FechaDocumento ,105) As FechaDocumento
		,CV.Cargo
		--,CV.Cargo As tituloCargo
		,CE.Descripcion As DescripcionCargo
		,CE.Titulo As TituloCargo
		,CE.Obligaciones As ObligacionesCargo
		--,cc.ciudad As CiudadFirma
		,cc.ciudad As CiudadFirmaContrato
		--,CV.Jornada
		--,CV.Jornada As CodJornada
		--,CV.Movilizacion
		--,CV.Movilizacion As AsignacionMovilizacion
		--,CV.SueldoBase
		,CV.querySiNoObs1
        --,CV.querySiNoObs2
        --,CV.querySiNoObs3
        --,CV.querySiNoObs4
        --,CV.querySiNoObs5
        --,CV.querySiNoDinamico1
        ,CV.querySiNoObs1_texto
        --,CV.querySiNoObs2_texto
        --,CV.querySiNoObs3_texto
        --,CV.querySiNoObs4_texto
        --,CV.querySiNoObs5_texto
        --,CV.querySiNoDinamico1_texto
		--,CV.tituloCargo
        --,CV.nombreEmpleado
        ,CV.Ciudad
        ,CV.DescripcionCargo
        ,CV.Duracion
        ,CONVERT(CHAR(10),CV.Fecha ,105) As Fecha
        ,CV.ModTeletrabajo
		,CV.Segmento
		,CV.PorcentajeBonoTarget
		,CV.MetaTargetS
		,CV.MetaTargetU

		,CV.ObjetivoFinanciero1
		,CV.DetalleObjetivoFinanciero1
		,CV.ObjetivoFinanciero2
		,CV.DetalleObjetivoFinanciero2
		,CV.MetaIndividual
		,CV.Bono
		,CV.Codigo
		,CV.Texto1 As Texto
		,CV.correoNotificacionPorConcentimiento AS CorreoNotificacion
		,CV.CiudadFirma
  FROM 
	ContratoDatosVariables CV
  INNER JOIN Contratos C ON C.idDocumento = CV.idDocumento
  INNER JOIN centroscosto cc ON CV.CentroCosto = cc.centrocostoid
  LEFT JOIN CargosEmpleado CE ON CV.Cargo = CE.idCargoEmpleado
  WHERE 
	 CV.idDocumento = @idDocumento  AND C.Eliminado = 0
	
END
GO
