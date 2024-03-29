USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_ObtenerTablaDatos_20210920]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- =============================================
-- Author:		<Author,,Name>
-- Create date: <Create Date,,>
-- Description:	sp_ObtenerTablaDatos 'DocumentosVariables', '907'
-- =============================================
CREATE PROCEDURE [dbo].[sp_ObtenerTablaDatos_20210920]
	@TablaDatos varchar(50),
	@NumeroContrato int
AS
BEGIN
	
	SET NOCOUNT ON;
	if (@TablaDatos = 'DocumentosVariables')
	BEGIN
		SELECT Top 1
		1 as "@variable@"
			--[NombreEmpresa] AS "@@NOMBREEMPRESA@@",
			--[NombreCliente] AS "@@NOMBRECLIENTE@@"
		   --convert (varchar(12),[InicioPeriodo],105)	AS "@@FechaDesde@@",
		   --convert (varchar(12),[TerminoPeriodo],105)	AS "@@FechaHasta@@",
		   --C.NumeroContrato	as "@@NumeroContrato@@", 	
		   --E.Rut				as "@@RutEmpleado@@",
		   --E.Nombre				as "@@NombreEmpleado@@"		  
		FROM ContratoDatosVariables D		
		where D.[idDocumento] = @NumeroContrato
	END
	if (@TablaDatos = 'datosPostulacion')
	BEGIN
		SELECT Top 1
		    --1 as "@variable@"
			--[NombreEmpresa] AS "@@NOMBREEMPRESA@@",
			--[NombreCliente] AS "@@NOMBRECLIENTE@@"
		   --convert (varchar(12),[InicioPeriodo],105)	AS "@@FechaDesde@@",
		   --convert (varchar(12),[TerminoPeriodo],105)	AS "@@FechaHasta@@",
		   --C.NumeroContrato	as "@@NumeroContrato@@", 	
		   Postulantes.rut				as "@@RutPostulante@@",
		   Postulantes.nombre				as "@@NombrePostulante@@",
		   CargosEmpresa.link				as "@@linkPostulacion@@"
		FROM EnvioCorreos
		INNER JOIN Postulaciones
		ON Postulaciones.postulacionid = EnvioCorreos.documentoid 
        INNER JOIN Postulantes
        ON Postulantes.postulanteid = postulaciones.postulanteid
        INNER JOIN CargosEmpresa
        ON CargosEmpresa.RutEmpresa = Postulaciones.RutEmpresa
        AND CargosEmpresa.idCargoEmpleado = Postulaciones.idCargoEmpleado
		WHERE EnvioCorreos.Correlativo = @NumeroContrato
	END
	if (@TablaDatos = 'datosResultadoPostulacion')
	BEGIN
		SELECT Top 1
		    --1 as "@variable@"
			--[NombreEmpresa] AS "@@NOMBREEMPRESA@@",
			--[NombreCliente] AS "@@NOMBRECLIENTE@@"
		   --convert (varchar(12),[InicioPeriodo],105)	AS "@@FechaDesde@@",
		   --convert (varchar(12),[TerminoPeriodo],105)	AS "@@FechaHasta@@",
		   --C.NumeroContrato	as "@@NumeroContrato@@", 	
		   Postulantes.rut				as "@@RutPostulante@@",
		   Postulantes.nombre				as "@@NombrePostulante@@"
		FROM EnvioCorreos
		INNER JOIN Postulaciones
		ON Postulaciones.postulacionid = EnvioCorreos.documentoid 
        INNER JOIN Postulantes
        ON Postulantes.postulanteid = postulaciones.postulanteid
		WHERE EnvioCorreos.Correlativo = @NumeroContrato
	END
	if (@TablaDatos = 'empleadoFormulario')
	BEGIN
		--Declare @NumContrato int, @RutUsuario varchar(14)
		--Select @NumContrato = documentoid, @RutUsuario = RutUsuario from EnvioCorreos where Correlativo = @NumeroContrato
		SELECT Top 1  
		   --E.empleadoid				as "@@RutPostulante@@"
		   P.personaid				AS "@@RutEmpleado@@"
		   ,P.nombre				AS "@@NombreEmpleado@@"
		FROM EnvioCorreos EN
		--INNER JOIN empleadoFormulario E ON E.empleadoFormularioid = EN.documentoid 
		INNER JOIN personas P on P.personaid = EN.RutUsuario
		WHERE EN.Correlativo = @NumeroContrato    
	END
	
END

/****** Object:  StoredProcedure [dbo].[sp_ObtenerDocumento]    Script Date: 10/02/2018 11:05:42 ******/
SET ANSI_NULLS ON
GO
