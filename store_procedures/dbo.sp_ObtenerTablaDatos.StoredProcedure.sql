USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_ObtenerTablaDatos]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		<Author,,Name>
-- Create date: <Create Date,,>
-- Description:	sp_ObtenerTablaDatos 'DocumentosVariables', '907'
-- =============================================
CREATE PROCEDURE [dbo].[sp_ObtenerTablaDatos]
	@TablaDatos varchar(50),
	@NumeroContrato int
AS
BEGIN
	SET NOCOUNT ON;
	if (@TablaDatos = 'DocumentosVariables')
	BEGIN
		SELECT Top 1
			1 as "@variable@",
			D.idDocumento as '@@NDocumento@@',
			(ISNULL(Personas.Nombre,'') + ' ' + ISNULL(Personas.appaterno,'') + ' ' + ISNULL(Personas.apmaterno,'')) AS "@@NombreEmpleado@@",
			usuarios.claveTemporal AS '@@PassTemp@@',
			TD.NombreTipoDoc as '@@NombreDocumento@@'
		FROM ContratoDatosVariables D	
		INNER JOIN Contratos C ON D.idDocumento = C.idDocumento
		INNER JOIN Plantillas PL ON C.idPlantilla = PL.idPlantilla
		INNER JOIN TipoDocumentos TD ON PL.idTipoDoc = TD.idTipoDoc
		INNER JOIN Personas
			ON Personas.personaid = D.Rut
		INNER JOIN usuarios
			ON usuarios.usuarioid = D.Rut
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
    IF (@TablaDatos = 'datosRRLL')
    BEGIN
		SELECT Top 1
            (Personas.Nombre + ' ' + Personas.appaterno + ' ' + Personas.apmaterno) AS "@@NombreFirmante@@",
			(Personas.Nombre + ' ' + Personas.appaterno + ' ' + Personas.apmaterno) AS "@@NombreRepresentante@@",
			(Personas.Nombre + ' ' + Personas.appaterno + ' ' + Personas.apmaterno) AS "@@NombreEmpleado@@",
            EnvioCorreos.documentoid AS "@@NDocumento@@",
            ContratoDatosVariables.rlTipoDocumento AS "@@NombreDocumento@@",
            Empresas.RazonSocial AS "@@RazonSocialCliente@@",
			usuarios.claveTemporal AS '@@PassTemp@@'
        FROM EnvioCorreos
        INNER JOIN ContratoDatosVariables ON ContratoDatosVariables.idDocumento = EnvioCorreos.documentoid
        INNER JOIN Personas ON Personas.personaid = EnvioCorreos.RutUsuario
        INNER JOIN usuarios ON usuarios.usuarioid = Personas.personaid
        INNER JOIN Contratos ON Contratos.idDocumento = EnvioCorreos.documentoid
        INNER JOIN Empresas ON Empresas.RutEmpresa = Contratos.RutEmpresa
        WHERE EnvioCorreos.Correlativo = @NumeroContrato
    END
    IF (@TablaDatos = 'datosBloqueoCuenta')
    BEGIN
		SELECT Top 1
            isnull(Personas.nombre,'') + ' ' + isnull(Personas.appaterno,'') As "@@NombreEmpleado@@"
        FROM EnvioCorreos
        INNER JOIN Personas ON Personas.personaid = EnvioCorreos.RutUsuario
        WHERE EnvioCorreos.Correlativo = @NumeroContrato
    END
	IF (@TablaDatos = 'empleadoFormulario')
	BEGIN
		SELECT Top 1
			(Personas.Nombre + ' ' + Personas.appaterno + ' ' + Personas.apmaterno) AS "@@NombreEmpleado@@",
			formularioPlantilla.nombreFormulario AS '@@NombreFormulario@@',
			usuarios.claveTemporal AS '@@PassTemp@@'
		FROM EnvioCorreos
		INNER JOIN empleadoFormulario
			ON empleadoFormulario.empleadoFormularioid = EnvioCorreos.documentoid 
			AND empleadoFormulario.empleadoFormularioid = @NumeroContrato
		INNER JOIN formularioPlantilla
			ON formularioPlantilla.idFormulario = empleadoFormulario.idFormulario
		INNER JOIN Personas
			ON Personas.personaid = empleadoFormulario.empleadoid
		INNER JOIN usuarios
			ON usuarios.usuarioid = empleadoFormulario.empleadoid
	END	
END
GO
