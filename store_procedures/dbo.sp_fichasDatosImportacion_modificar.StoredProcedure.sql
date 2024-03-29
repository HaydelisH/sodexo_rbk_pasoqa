USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_fichasDatosImportacion_modificar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_fichasDatosImportacion_modificar]

	@pfichaid INT,
	@pusuarioid VARCHAR(10),
	@pRutEmpresa	VARCHAR(10),
	@pCodDivPersonal	NVARCHAR(14),
	@pCodCargo	NVARCHAR(14),
	@pClaseContrato	VARCHAR(50),
	@pRolEmpleado	INT,
	@pTipoMovimiento	INT,
	@pCodJornada	NVARCHAR(14),
	@pEstadoEmpleado	INT,
	@pAsignacionMovilizacion	NVARCHAR(14),
	@pPosicion	NVARCHAR(14),
	@pNacionalidad	VARCHAR(50),
	@pAsignacionPerdidaCajaValor	NVARCHAR(14),
	@pRutTrabajador	VARCHAR(10),
	@pComuna	VARCHAR(50),
	@pAsignacionPerdidaCajaPorcentaje	NVARCHAR(14),
	@pDireccion	VARCHAR(100),
	@pNombreTrabajador	VARCHAR(100),
	@pCiudadFirma	VARCHAR(50),
	@pAsignacionColacion	NVARCHAR(14),
	@pDescCargoRIOHS	NVARCHAR(25),
	@pSueldoBase	NVARCHAR(14),
	@pFechaNacimiento	DATE,
	@pCorreoElectronicoEmpleado	VARCHAR(60),
	@pFechaTermino1	DATE,
	@pFechaTermino2	DATE,
	@pEstadoCivil	INT,
	@pAreaTrabajo	VARCHAR(50),
	@pFechaInicioContrato	DATE
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje VARCHAR(100)
	DECLARE @error INT
	
	IF EXISTS ( SELECT fichaid FROM fichasDatosImportacion WHERE fichaid = @pfichaid ) 
		BEGIN
		
			UPDATE fichasDatosImportacion SET 
				FechaModificacion	=	GETDATE(),
				RutUsuario = @pusuarioid,
				RutEmpresa	=	@pRutEmpresa,
				CodDivPersonal	=	@pCodDivPersonal,
				CodCargo	=	@pCodCargo,
				ClaseContrato	=	@pClaseContrato,
				RolEmpleado	=	@pRolEmpleado,
				TipoMovimiento	=	@pTipoMovimiento,
				CodJornada	=	@pCodJornada,
				EstadoEmpleado	=	@pEstadoEmpleado,
				AsignacionMovilizacion	=	@pAsignacionMovilizacion,
				Posicion	=	@pPosicion,
				Nacionalidad	=	@pNacionalidad,
				AsignacionPerdidaCajaValor	=	@pAsignacionPerdidaCajaValor,
				RutTrabajador	=	@pRutTrabajador,
				Comuna	=	@pComuna,
				AsignacionPerdidaCajaPorcentaje	=	@pAsignacionPerdidaCajaPorcentaje,
				Direccion	=	@pDireccion,
				NombreTrabajador	=	@pNombreTrabajador,
				CiudadFirma	=	@pCiudadFirma,
				AsignacionColacion	=	@pAsignacionColacion,
				DescCargoRIOHS	=	@pDescCargoRIOHS,
				SueldoBase	=	@pSueldoBase,
				FechaNacimiento	=	@pFechaNacimiento,
				CorreoElectronicoEmpleado	=	@pCorreoElectronicoEmpleado,
				FechaTermino1	=	@pFechaTermino1,
				FechaTermino2	=	@pFechaTermino2,
				EstadoCivil	=	@pEstadoCivil,
				AreaTrabajo	=	@pAreaTrabajo,
				FechaInicioContrato	=	@pFechaInicioContrato
			WHERE
				fichaid = @pfichaid
			
			SET @lmensaje = ''
			SET @error = 0

		END
	ELSE
		BEGIN 
			SET @lmensaje = 'La ficha seleccionada no existe'
			SET @error = 1 	
		END
		
	SELECT @lmensaje As mensaje, @error As error

END
GO
