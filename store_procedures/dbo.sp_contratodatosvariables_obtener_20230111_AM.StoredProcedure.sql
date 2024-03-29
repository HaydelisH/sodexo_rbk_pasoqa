USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_contratodatosvariables_obtener_20230111_AM]    Script Date: 1/22/2024 7:21:13 PM ******/
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
CREATE PROCEDURE [dbo].[sp_contratodatosvariables_obtener_20230111_AM]
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
		,CV.Jornada
		--,CV.Jornada As CodJornada
		--,CV.Movilizacion
		--,CV.Movilizacion As AsignacionMovilizacion
		--,CV.SueldoBase
		--,CV.querySiNoObs1
        --,CV.querySiNoObs2
        --,CV.querySiNoObs3
        --,CV.querySiNoObs4
        --,CV.querySiNoObs5
        --,CV.querySiNoDinamico1
       -- ,CV.querySiNoObs1_texto
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
		,CV.Texto1
		,CV.correoNotificacionPorConcentimiento AS CorreoNotificacion
		,CV.CiudadFirma

		,CV.nombreJefeDirecto
		,CV.cargoJefeDirecto
		,CV.nombreDirectorSegmento
		,CV.directorDeSegmento

		,CV.Texto2
		,CV.Texto3
		,CV.Texto4
		,CV.Porcentaje1
		,CV.Porcentaje2
		,CV.Porcentaje3
		,CV.Porcentaje4
		,CV.Stretch1
		,CV.Stretch2

		,CV.querySiNoObs1
        ,CV.querySiNoObs2
        ,CV.querySiNoObs3
        ,CV.querySiNoObs4
        ,CV.querySiNoObs5
        ,CV.querySiNoObs6
		,CV.querySiNoObs7
		,CV.querySiNoObs8
        ,CV.querySiNoObs1_texto
        ,CV.querySiNoObs2_texto
        ,CV.querySiNoObs3_texto
        ,CV.querySiNoObs4_texto
        ,CV.querySiNoObs5_texto
		,CV.querySiNoObs6_texto
		,CV.querySiNoObs7_texto
		,CV.querySiNoObs8_texto

		,case CV.querySiNoObs1 when 'no' then '_X_' else '___' end as respquery1
		,case CV.querySiNoObs1 when 'si' then '_X_' else '___' end as respquery2
		,case CV.querySiNoObs2 when 'no' then '_X_' else '___' end as respquery3
		,case CV.querySiNoObs2 when 'si' then '_X_' else '___' end as respquery4
		,case CV.querySiNoObs3 when 'no' then '_X_' else '___' end as respquery5
		,case CV.querySiNoObs3 when 'si' then '_X_' else '___' end as respquery6
		,case CV.querySiNoObs4 when 'no' then '_X_' else '___' end as respquery7
		,case CV.querySiNoObs4 when 'si' then '_X_' else '___' end as respquery8
		,case CV.querySiNoObs5 when 'no' then '_X_' else '___' end as respquery9
		,case CV.querySiNoObs5 when 'si' then '_X_' else '___' end as respquery10
		,case CV.querySiNoObs6 when 'no' then '_X_' else '___' end as respquery11
		,case CV.querySiNoObs6 when 'si' then '_X_' else '___' end as respquery12
		,case CV.querySiNoObs7 when 'no' then '_X_' else '___' end as respquery13
		,case CV.querySiNoObs7 when 'si' then '_X_' else '___' end as respquery14
		,case CV.querySiNoObs8 when 'no' then '_X_' else '___' end as respquery15
		,case CV.querySiNoObs8 when 'si' then '_X_' else '___' end as respquery16

		,case CV.cbox1 when 'X' then '_X_' else '___' end as cbox1
		,case CV.cbox2 when 'X' then '_X_' else '___' end as cbox2
		,case CV.cbox3 when 'X' then '_X_' else '___' end as cbox3
  
  FROM	 
	ContratoDatosVariables CV
  INNER JOIN Contratos C ON C.idDocumento = CV.idDocumento
  INNER JOIN centroscosto cc ON CV.CentroCosto = cc.centrocostoid and CV.lugarpagoid = cc.lugarpagoid 
  LEFT JOIN CargosEmpleado CE ON CV.Cargo = CE.idCargoEmpleado
  WHERE 
	 CV.idDocumento = @idDocumento  AND C.Eliminado = 0
	
END
GO
