USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_fichasDatosImportacion_obtener]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		Haydelis Hernández
-- Create date: 05-07-2019
-- Description:	Obtener de fichasDatosImportacion
-- Ejemplo: sp_fichasDatosImportacion_obtener 2
-- =============================================
CREATE PROCEDURE [dbo].[sp_fichasDatosImportacion_obtener]

	@pfichaid INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	SELECT 
		cc.empresaid as RutEmpresa			
		,CONVERT(char(10), f.FechaNacimiento, 105) AS fechanacimiento			
		,CONVERT(VARCHAR(10),f.FechaCreacion,105) AS FechaCreacion			
		,CONVERT(VARCHAR(10),f.FechaInicioContrato,105) AS FechaInicio			
		,CONVERT(VARCHAR(10),f.FechaInicioContrato,105) AS FechaInicioContrato			
		,CONVERT(VARCHAR(10),f.FechaModificacion,105) AS FechaModificacion			
		,CONVERT(VARCHAR(10),f.FechaNacimiento,105) AS FechaNacimiento			
		,CONVERT(VARCHAR(10),f.FechaTermino1,105) AS FechaTermino			
		,CONVERT(VARCHAR(10),f.FechaTermino1,105) AS FechaTermino1			
		,CONVERT(VARCHAR(10),f.FechaTermino2,105) AS FechaTermino2			
		,ef.nombreestado As EstadoFicha			
		,f.ApMatTrabajador as apmaterno			
		,f.ApPatTrabajador as appaterno			
		,f.AreaTrabajo			
		,f.AsignacionCajaFija			
		,f.AsignacionColacion			
		,f.AsignacionMovilizacion			
		,f.AsignacionMovilizacion As Movilizacion			
		,f.AsignacionPerdidaCajaPorcentaje			
		,f.AsignacionPerdidaCajaValor			
		,f.CiudadFirma			
		,f.CiudadFirma As Ciudad			
		,f.CiudadTrabajador as ciudad			
		,f.ClaseContrato			
		,f.CodCargo			
		,f.CodCargo As Cargo			
		,f.CodDivPersonal
		,f.CodDivPersonal As idCentroCosto
		,f.CodJornada			
		,f.CodJornada As Jornada			
		,f.Comuna			
		,f.Comuna as comuna			
		,f.CorreoElectronicoEmpleado			
		,f.CorreoElectronicoEmpleado as correo			
		,f.DescCargoRIOHS	
		,f.DescCargoRIOHS as DescCargoRiohs
		,f.Direccion			
		,f.Direccion as direccion			
		,f.EstadoCivil			
		,f.EstadoCivil AS idEstadoCivil			
		,f.EstadoEmpleado			
		,f.idEstado			
		,f.Nacionalidad			
		,f.Nacionalidad as nacionalidad			
		,f.NombreTrabajador			
		,f.NombreTrabajador as nombre			
		,f.Posicion			
		,f.RolEmpleado			
		,f.RolEmpleado as rolid			
		,f.RutTrabajador			
		,f.RutTrabajador as newusuarioid			
		,f.RutTrabajador as personaid			
		,f.RutUsuario			
		,f.SueldoBase			
		,f.TipoMovimiento			
		,f.fichaid			
	FROM
		fichasDatosImportacion f
		INNER JOIN centroscosto cc ON f.CodDivPersonal = cc.centrocostoid
		INNER JOIN EstadosFichas ef ON f.idEstado = ef.estadoid
	WHERE 
		fichaid = @pfichaid
END
GO
