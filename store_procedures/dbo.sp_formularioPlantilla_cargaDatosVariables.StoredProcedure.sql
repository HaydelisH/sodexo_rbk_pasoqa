USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_formularioPlantilla_cargaDatosVariables]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez 
-- Creado el: 29-04-2019
-- Descripcion: Agregar las variables de un documento 
-- Modificado: gdiaz 11/04/2021
-- Ejemplo:exec [sp_formularioPlantilla_cargaDatosVariables] 
-- =============================================
--NUEVO
CREATE PROCEDURE [dbo].[sp_formularioPlantilla_cargaDatosVariables]
	-- Identificador de tabla empleadoFormulario
    @empleadoFormularioid INT ,
    -- Datos que van a la tabla FormularioPersonas
	@personaid	VARCHAR(10)	,
    @nombre VARCHAR(110),
    @appaterno VARCHAR(50),
    @apmaterno VARCHAR(50),
	@nacionalidad	VARCHAR(20)	,
	@fechanacimiento DATE,
	@idEstadoCivil int	,
	@direccion VARCHAR(50)	,
    @comuna	VARCHAR(30)	,
	@ciudad	VARCHAR(20)	,
	@rolid	int,
	@correo	VARCHAR(60)	,
    -- Datos que van a la tabla FormularioDatosVariables
    @empresaid	VARCHAR(10)	,
	@centrocostoid	VARCHAR(14)	,
	@lugarpagoid VARCHAR(14),
    @CiudadFirma	VARCHAR(20)	,
    @FechaDocumento DATE,
    @FechaIngreso DATE,
    @Cargo VARCHAR(50),
    @LeFirmantes VARCHAR(max)
AS
BEGIN	
	SET NOCOUNT ON;	

	DECLARE @lmensaje VARCHAR(100)
	DECLARE @error INT;
	
	--IF NOT EXISTS ( SELECT idDocumento FROM ContratoDatosVariables WHERE idDocumento = @pidDocumento ) 
		BEGIN 
			INSERT INTO FormularioPersonas(
				empleadoFormularioid,
				personaid,
				nombre,
                appaterno,
                apmaterno,
				nacionalidad,
				fechanacimiento,
				estadocivil,
				direccion,
				comuna,
				ciudad,
				rol,
				correo
            )
			VALUES(
				@empleadoFormularioid,
				@personaid,
				@nombre,
                @appaterno,
                @apmaterno,
				@nacionalidad,
				@fechanacimiento,
				@idEstadoCivil,
				@direccion,
				@comuna,
				@ciudad,
				@rolid,
				@correo
            )
					
			INSERT INTO FormularioDatosVariables(
				empleadoFormularioid,
				empresaid,
				centrocostoid,
				lugarpagoid,
                CiudadFirma,
                FechaDocumento,
                FechaIngreso,
                Cargo,
                FirmantesJSON
            )
			VALUES(
				@empleadoFormularioid,
				@empresaid,
				@centrocostoid,
				@lugarpagoid,
                @CiudadFirma,
                @FechaDocumento,
                @FechaIngreso,
                @Cargo,
                @LeFirmantes
            )
            SET @lmensaje = ''
            SET @error = 0						
		END
	/*ELSE
		BEGIN
			UPDATE ContratoDatosVariables SET 
				Rut = @pRut,
				CentroCosto = @pCentroCosto,
				Fecha = @pFecha,
				Cargo = @pCargo,
				Ciudad = @pCiudad,
				FechaIngreso = @pFechaIngreso,
				FechaInicio = @pFechaInicio,
				Gratificacion = @pGratificacion,
				Jornada = @pJornada,
				Movilizacion = @pMovilizacion,
				SueldoBase = @pSueldoBase,
				Texto1 = @pTexto1,
				lugarpagoid = @pLugarPagoId,
				AnticipoIndemnizacion	=	@AnticipoIndemnizacion,
				BonoAnillo1	=	@BonoAnillo1,
				BonoAnillo2	=	@BonoAnillo2,
				BonoAnillo3	=	@BonoAnillo3,
				BonoAsistencia	=	@BonoAsistencia,
				BonoDisponibilidad	=	@BonoDisponibilidad,
				BonoImagen	=	@BonoImagen,
				BonoMixer	=	@BonoMixer,
				BonoOperadorPlanta	=	@BonoOperadorPlanta,
				BonoProductividad	=	@BonoProductividad,
				BonoProgramadorPlanta	=	@BonoProgramadorPlanta,
				BonoProyectoEspecial	=	@BonoProyectoEspecial,
				BonoProyectoEspecial2	=	@BonoProyectoEspecial2,
				BonoSucursal	=	@BonoSucursal,
				Colacion	=	@Colacion,
				Entidad	=	@Entidad,
				Indemnizacion	=	@Indemnizacion,
				MesBonoProgramadorPlanta	=	@MesBonoProgramadorPlanta,
				MontoActual	=	@MontoActual,
				MontoFinal	=	@MontoFinal,
				MontoNuevo	=	@MontoNuevo,
				NombreCurso	=	@NombreCurso,
				NombreProyecto	=	@NombreProyecto,
				PagoIas	=	@PagoIas,
				SaldoIas	=	@SaldoIas,
				SaldoIndemnizacion	=	@SaldoIndemnizacion,
				TipoEstudios	=	@TipoEstudios,
				ValorBeca	=	@ValorBeca,
				Bono = @Bono,
				FechaPago = @FechaPago,
				Lugar = @Lugar,
				NumeroAnillos = @NumeroAnillos,
				PlantaDireccion  = @PlantaDireccion,
				TotalRemuneracion = @TotalRemuneracion,
				FechaTermino = @FechaTermino,
				FondoFijo = @FondoFijo,
                querySiNoObs1 = @querySiNoObs1,
                querySiNoObs2 = @querySiNoObs2,
                querySiNoObs3 = @querySiNoObs3,
                querySiNoObs4 = @querySiNoObs4,
                querySiNoObs5 = @querySiNoObs5,
                querySiNoDinamico1 = @querySiNoDinamico1,
                querySiNoObs1_texto = @querySiNoObs1_texto,
                querySiNoObs2_texto = @querySiNoObs2_texto,
                querySiNoObs3_texto = @querySiNoObs3_texto,
                querySiNoObs4_texto = @querySiNoObs4_texto,
                querySiNoObs5_texto = @querySiNoObs5_texto,
                querySiNoDinamico1_texto = @querySiNoDinamico1_texto,
                tituloCargo = @tituloCargo,
                nombreEmpleado = @nombreEmpleado,
                FechaActual = @FechaActual,
                --DiasHabiles = @DiasHabiles,
				querySiNoObsDes1 = @querySiNoObsDes1,
				querySiNoObsDes1_texto = @querySiNoObsDes1_texto
			WHERE 
				idDocumento = @pidDocumento 
				
			SET @lmensaje = ''
			SET @error = 0	
		END */
	SELECT @error AS error, @lmensaje AS mensaje 
END
GO
