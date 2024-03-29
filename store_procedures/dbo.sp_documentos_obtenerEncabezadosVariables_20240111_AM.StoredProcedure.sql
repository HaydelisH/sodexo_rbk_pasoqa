USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_obtenerEncabezadosVariables_20240111_AM]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- =============================================
-- Autor: Haydelis Hernandez 
-- Creado el: 25/03/2019
-- Descripcion: Obtiene las variables diponibles de un documento subido por carga masiva 
-- Ejemplo:exec [sp_documentos_obtenerEncabezadosVariables] 
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_obtenerEncabezadosVariables_20240111_AM]
AS
BEGIN	
	SET NOCOUNT ON;			
 
	SELECT TOP 1
		--cc.ciudad As CiudadLocal
		--,cc.comuna As ComunaLocal
		--,cc.direccion As DireccionLocal
		--,cc.nombrecentrocosto As NombreLocal
		1 As FechaInicio
		,1 As Fecha
		--,CONVERT(CHAR(10),CV.FechaInicio ,105) As FechaInicioContrato
		--,CONVERT(CHAR(10),CV.FechaRetorno ,105) As FechaRetorno
		,1 As FechaTermino
		--,CONVERT(CHAR(10),CV.FechaTermino ,105) As FechaTermino1
		--,CONVERT(CHAR(10),CV.FechaTermino2 ,105) As FechaTermino2
		,1 As FechaIngreso
		--,CV.AreaTrabajo
		--,CV.AsignacionCajaFija
		--,CV.AsignacionColacion
		--,CV.AsignacionPerdidaCajaPorcentaje
		--,CV.AsignacionPerdidaCajaValor
		,1 As Cargo
		--,CV.Cargo As CodCargo
        --,CV.DescripcionCargo
        ,1 AS Ciudad
        --,CV.BonoAsistencia
        --,CV.Colacion
        --,CV.ContratoComercial
        --,CV.DescripcionCargo
        --,CV.Descuento
        --,CV.DireccionCliente
        --,CV.Jefatura
        --,CV.NombreCliente
        --,CV.ObligacionesCargo
		,1 As DescripcionCargo
		,1 As TituloCargo
		,1 As ObligacionesCargo
		--,cc.ciudad As CiudadFirma
		,1 As CiudadFirmaContrato
		--,CV.ClaseContrato
		,1 As CentroCosto
		--,CV.CentroCosto As CodDivPersonal
		--,CV.DescCargoRiohs
		,1 As Jornada
		--,CV.Jornada As CodJornada
		,1 As Movilizacion
		--,CV.Movilizacion As AsignacionMovilizacion
		--,CV.Posicion
		,1 As SueldoBase
		--,CV.TipoMovimiento
		,1 As Texto1
		--,CV.Texto2
		--,CV.Bono
		,1 As BonoResponsabilidad
		,1 As TituloCargo
		--,CV.TramoCliente
		--,CV.Anno As Año
		--,CV.Anno As Anno
		--,CV.Numero
		--,CV.Celular
		,1 As FechaDocumento
		--,CONVERT(CHAR(10),CV.FechaInicioProrroga ,105) As FechaInicioProrroga
		--,CONVERT(CHAR(10),CV.FechaTerminoProrroga ,105) As FechaTerminoProrroga
		--,CV.Monto
		--,CV.NumeroCuotas
		--,CV.DiasATomar
		--,CONVERT(CHAR(10),CV.FechaInicioVacaciones ,105) As FechaInicioVacaciones
		--,CONVERT(CHAR(10),CV.FechaRetornoVacaciones ,105) As FechaRetornoVacaciones
		--,CONVERT(CHAR(10),CV.FechaTerminoVacaciones ,105) As FechaTerminoVacaciones
		--,CV.SaldoVacaciones
        ,1 As Duracion
        ,1 As ModTeletrabajo
		,1 AS Segmento
		,1 AS PorcentajeBonoTarget
		,1 AS MetaTargetS
		,1 AS MetaTargetU
		,1 AS ObjetivoFinanciero1
		,1 AS DetalleObjetivoFinanciero1
		,1 AS ObjetivoFinanciero2
		,1 AS DetalleObjetivoFinanciero2
		,1 AS MetaIndividual

		,1 AS PorcentajeBonoTarget
		,1 AS Bono
        
		,1 As CorreoNotificacion
		,1 As CiudadFirma

		,1 AS nombreJefeDirecto	
		,1 AS cargoJefeDirecto
		,1 AS nombreDirectorSegmento
		,1 AS directorDeSegmento

		,1 AS Texto2	
		,1 AS Texto3
		,1 AS Texto4
		,1 AS Porcentaje1
		,1 AS Porcentaje2	
		,1 AS Porcentaje3
		,1 AS Porcentaje4
		,1 AS Stretch1
		,1 AS Stretch2

		,1 AS Porcentaje5
		,1 AS Porcentaje6
		,1 AS Texto5
		,1 AS Jornada2

		,1 AS AplicaStrech1	
		,1 AS AplicaStrech2	
		,1 AS Stretch3		
		,1 AS Stretch4		
		,1 AS Booster			
		,1 AS Modificador	
		,1 AS Texto6

		
END
GO
