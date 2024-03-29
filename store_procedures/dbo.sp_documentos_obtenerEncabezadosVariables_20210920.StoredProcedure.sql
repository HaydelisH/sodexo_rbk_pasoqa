USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_obtenerEncabezadosVariables_20210920]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- =============================================
-- Autor: Haydelis Hernandez 
-- Creado el: 25/03/2019
-- Descripcion: Obtiene las variables diponibles de un documento subido por carga masiva 
-- Ejemplo:exec [sp_contratodatosvariables_obtener] 
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_obtenerEncabezadosVariables_20210920]
AS
BEGIN	
	SET NOCOUNT ON;			
 
	SELECT TOP 1
		-- cc.ciudad As CiudadLocal
		--,cc.comuna As ComunaLocal
		--,cc.direccion As DireccionLocal
		--,cc.nombrecentrocosto As NombreLocal
		CONVERT(CHAR(10),CV.FechaInicio ,105) As FechaInicio
		,CONVERT(CHAR(10),CV.Fecha ,105) As Fecha
		--,CONVERT(CHAR(10),CV.FechaInicio ,105) As FechaInicioContrato
		--,CONVERT(CHAR(10),CV.FechaRetorno ,105) As FechaRetorno
		,CONVERT(CHAR(10),CV.FechaTermino ,105) As FechaTermino
		--,CONVERT(CHAR(10),CV.FechaTermino ,105) As FechaTermino1
		--,CONVERT(CHAR(10),CV.FechaTermino2 ,105) As FechaTermino2
		,CONVERT(CHAR(10),CV.FechaIngreso ,105) As FechaIngreso
		--,CV.AreaTrabajo
		--,CV.AsignacionCajaFija
		--,CV.AsignacionColacion
		--,CV.AsignacionPerdidaCajaPorcentaje
		--,CV.AsignacionPerdidaCajaValor
		,CV.Cargo
		--,CV.Cargo As CodCargo
        --,CV.DescripcionCargo
        --,CV.ciudad2 AS Ciudad
        --,CV.BonoAsistencia
        --,CV.Colacion
        --,CV.ContratoComercial
        --,CV.DescripcionCargo
        --,CV.Descuento
        --,CV.DireccionCliente
        --,CV.Jefatura
        --,CV.NombreCliente
        --,CV.ObligacionesCargo
		--,CE.Descripcion As DescripcionCargo
		--,CE.Titulo As TituloCargo
		--,CE.Obligaciones As ObligacionesCargo
		--,cc.ciudad As CiudadFirma
		--,cc.ciudad As CiudadFirmaContrato
		--,CV.ClaseContrato
		,CV.CentroCosto As CentroCosto
		--,CV.CentroCosto As CodDivPersonal
		--,CV.DescCargoRiohs
		,CV.Jornada
		--,CV.Jornada As CodJornada
		,CV.Movilizacion
		--,CV.Movilizacion As AsignacionMovilizacion
		--,CV.Posicion
		,CV.SueldoBase
		--,CV.TipoMovimiento
		,CV.Texto1
		--,CV.Texto2
		--,CV.Bono
		,CV.BonoResponsabilidad
		,CV.TituloCargo
		--,CV.TramoCliente
		--,CV.Anno As Año
		--,CV.Anno As Anno
		--,CV.Numero
		--,CV.Celular
		--,CONVERT(CHAR(10),CV.FechaDocumento ,105) As FechaDocumento
		--,CONVERT(CHAR(10),CV.FechaInicioProrroga ,105) As FechaInicioProrroga
		--,CONVERT(CHAR(10),CV.FechaTerminoProrroga ,105) As FechaTerminoProrroga
		--,CV.Monto
		--,CV.NumeroCuotas
		--,CV.DiasATomar
		--,CONVERT(CHAR(10),CV.FechaInicioVacaciones ,105) As FechaInicioVacaciones
		--,CONVERT(CHAR(10),CV.FechaRetornoVacaciones ,105) As FechaRetornoVacaciones
		--,CONVERT(CHAR(10),CV.FechaTerminoVacaciones ,105) As FechaTerminoVacaciones
		--,CV.SaldoVacaciones
  FROM 
	ContratoDatosVariables CV
  LEFT JOIN Contratos C ON C.idDocumento = CV.idDocumento
  LEFT JOIN centroscosto cc ON CV.CentroCosto = cc.centrocostoid
  LEFT JOIN CargosEmpleado CE ON CV.Cargo = CE.idCargoEmpleado

END
GO
