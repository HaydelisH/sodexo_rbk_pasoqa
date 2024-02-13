USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_fichasDatosImportacion_agregar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:                           Haydelis Hernández
-- Create date: 05-07-2019
-- Description:   Agregar fichasDatosImportacion
-- Ejemplo: 
-- =============================================
CREATE PROCEDURE [dbo].[sp_fichasDatosImportacion_agregar]
                
    @pRutEmpresa               VARCHAR(10),
    @pCodDivPersonal        NVARCHAR(14),
    @pCodCargo     NVARCHAR(14),
    @pClaseContrato           VARCHAR(50),
    @pRolEmpleado             INT,
    @pTipoMovimiento      INT,
    @pCodJornada NVARCHAR(14),
    @pEstadoEmpleado      VARCHAR(1),
    @pAsignacionMovilizacion         NVARCHAR(14),
    @pPosicion        NVARCHAR(14),
    @pNacionalidad              VARCHAR(50),
    @pAsignacionPerdidaCajaValor               NVARCHAR(14),
    @pRutTrabajador           VARCHAR(10),
    @pComuna       VARCHAR(50),
    @pAsignacionPerdidaCajaPorcentaje   NVARCHAR(14),
    @pDireccion      VARCHAR(100),
    @pNombreTrabajador VARCHAR(100),
    @pCiudadFirma               VARCHAR(50),
    @pAsignacionColacion NVARCHAR(14),
    @pDescCargoRIOHS      NVARCHAR(25),
    @pSueldoBase NVARCHAR(14),
    @pFechaNacimiento     DATE,
    @pCorreoElectronicoEmpleado               VARCHAR(60),
    @pFechaTermino1         DATE,
    @pFechaTermino2         DATE,
    @pEstadoCivil   INT,
    @pAreaTrabajo               VARCHAR(50),
    @pFechaInicioContrato               DATE,
    @pCiudadTrabajador    VARCHAR(50),
    @pAssignacionCajaFija NVARCHAR(14),
    @pApPatTrabajador      VARCHAR(50),
    @pApMatTrabajador    VARCHAR(50)

AS
BEGIN
                -- SET NOCOUNT ON added to prevent extra result sets from
                -- interfering with SELECT statements.
                SET NOCOUNT ON;
                
                DECLARE @idEstado INT
                
                SET @idEstado = 1 --Pendiente

    INSERT INTO fichasDatosImportacion
                (              idEstado,
                               FechaCreacion,
                               RutEmpresa,
                               CodDivPersonal,
                               CodCargo,
                               ClaseContrato,
                               RolEmpleado,
                               TipoMovimiento,
                               CodJornada,
                               EstadoEmpleado,
                               AsignacionMovilizacion,
                               Posicion,
                               Nacionalidad,
                               AsignacionPerdidaCajaValor,
                               RutTrabajador,
                               Comuna,
                               AsignacionPerdidaCajaPorcentaje,
                               Direccion,
                               NombreTrabajador,
                               CiudadFirma,
                               AsignacionColacion,
                               DescCargoRIOHS,
                               SueldoBase,
                               FechaNacimiento,
                               CorreoElectronicoEmpleado,
                               FechaTermino1,
                               FechaTermino2,
                               EstadoCivil,
                               AreaTrabajo,
                               FechaInicioContrato,
                               CiudadTrabajador,
                               AsignacionCajaFija,
                               ApPatTrabajador,
                               ApMatTrabajador
                )VALUES(
                               @idEstado,
                               GETDATE(),
                               @pRutEmpresa,
                               @pCodDivPersonal,
                               @pCodCargo,
                               @pClaseContrato,
                               @pRolEmpleado,
                               --@pTipoMovimiento,
							   1,
                               @pCodJornada,
                               @pEstadoEmpleado,
                               @pAsignacionMovilizacion,
                               @pPosicion,
                               @pNacionalidad,
                               @pAsignacionPerdidaCajaValor,
                               @pRutTrabajador,
                               @pComuna,
                               @pAsignacionPerdidaCajaPorcentaje,
                               @pDireccion,
                               @pNombreTrabajador,
                               @pCiudadFirma,
                               @pAsignacionColacion,
                               @pDescCargoRIOHS,
                               @pSueldoBase,
                               @pFechaNacimiento,
                               @pCorreoElectronicoEmpleado,
                               @pFechaTermino1,
                               @pFechaTermino2,
                               @pEstadoCivil,
                               @pAreaTrabajo,
                               @pFechaInicioContrato,
                               @pCiudadTrabajador,
                               @pAssignacionCajaFija,
                               @pApPatTrabajador,
                               @pApMatTrabajador
                )

END

/****** Object:  StoredProcedure [dbo].[sp_estadosempleados_obtener]    Script Date: 07/22/2019 12:10:34 ******/
SET ANSI_NULLS ON
GO
