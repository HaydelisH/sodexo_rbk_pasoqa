USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_contratodatosvariables_agregar_20211110_AM]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


-- =============================================
-- Autor: Haydelis Hernandez 
-- Creado el: 29-04-2019
-- Descripcion: Agregar las variables de un documento 
-- Modificado: gdiaz 11/04/2021
-- Ejemplo:exec [sp_contratodatosvariables_agregar] 
-- =============================================
CREATE PROCEDURE [dbo].[sp_contratodatosvariables_agregar_20211110_AM]
	@pidDocumento	INT,
	@Rut	VARCHAR(10),
	@CentroCosto	NVARCHAR(14),
	@lugarpagoid	NVARCHAR(14),
	--@departamentoid	NVARCHAR(14),
	@Cargo	VARCHAR(100),
	--@Jornada	VARCHAR(10),
	--@Movilizacion	INT,
	--@SueldoBase	NVARCHAR(14),
	--@FechaTermino	DATE,
	@FechaInicio	DATE,
	@FechaIngreso DATE,
	@FechaDocumento DATE,
	@querySiNoObs1 nvarchar(2),
    --@querySiNoObs2 nvarchar(2),
    --@querySiNoObs3 nvarchar(2),
    --@querySiNoObs4 nvarchar(2),
    --@querySiNoObs5 nvarchar(2),
    --@querySiNoDinamico1 nvarchar(2),
	@querySiNoObs1_texto nvarchar(2500),
    --@querySiNoObs2_texto nvarchar(2500),
    --@querySiNoObs3_texto nvarchar(2500),
    --@querySiNoObs4_texto nvarchar(2500),
    --@querySiNoObs5_texto nvarchar(2500),
    --@querySiNoDinamico1_texto nvarchar(max),
	--@tituloCargo nvarchar(100),
    --@nombreEmpleado nvarchar(110)
    @Ciudad varchar(20),
    @DescripcionCargo varchar(100),
    @Duracion varchar(20),
    @Fecha DATE,
    @ModTeletrabajo varchar(60),
	@Segmento NVARCHAR(30),
	@PorcentajeBonoTarget NVARCHAR(14),
	@MetaTargetS NVARCHAR(50),
	@MetaTargetU NVARCHAR(50),

	@ObjetivoFinanciero1 varchar(50),
	@DetalleObjetivoFinanciero1 varchar(50),
	@ObjetivoFinanciero2 varchar(50),
	@DetalleObjetivoFinanciero2 varchar(50),
	@MetaIndividual varchar(400),
	@Bono NVARCHAR(14),
	@Codigo NVARCHAR(14),
	@Texto1 VARCHAR(2000),
	@rlRutProveedor VARCHAR(10),
	@rlTipoDocumento VARCHAR(200),

	@correoNotificacionPorConcentimiento VARCHAR(60),

    @CiudadFirma varchar(20)
AS
BEGIN	
	SET NOCOUNT ON;	

	DECLARE @lmensaje VARCHAR(100)
	DECLARE @error INT
	DECLARE @CodDivPersonal NVARCHAR(14);
	
	SELECT @CodDivPersonal = @CentroCosto
	
	IF NOT EXISTS ( SELECT idDocumento FROM ContratoDatosVariables WHERE idDocumento = @pidDocumento ) 
		BEGIN 
			INSERT INTO ContratoDatosVariables(
				idDocumento,
				Rut,
				CentroCosto,
				lugarpagoid,
				--departamentoid,
				Cargo,
				--Jornada,
				--Movilizacion,
				--SueldoBase,
				--FechaTermino,
				FechaInicio,
				FechaIngreso,
				FechaDocumento,
				querySiNoObs1,
                --querySiNoObs2,
                --querySiNoObs3,
                --querySiNoObs4,
                --querySiNoObs5,
                --querySiNoDinamico1,
                querySiNoObs1_texto,
                --querySiNoObs2_texto,
                --querySiNoObs3_texto,
                --querySiNoObs4_texto,
                --querySiNoObs5_texto,
                --querySiNoDinamico1_texto,
				--tituloCargo,
                --nombreEmpleado
                Ciudad,
                DescripcionCargo,
                Duracion,
                Fecha,
                ModTeletrabajo,
				Segmento,
				PorcentajeBonoTarget,
				MetaTargetS,
				MetaTargetU,

				ObjetivoFinanciero1,
				DetalleObjetivoFinanciero1,
				ObjetivoFinanciero2,
				DetalleObjetivoFinanciero2,
				MetaIndividual,
				Bono,
				Codigo,
				Texto1,
				rlRutProveedor,
				rlTipoDocumento,

				correoNotificacionPorConcentimiento,

				CiudadFirma
				)
			VALUES(
				@pidDocumento,
				@Rut,
				@CentroCosto,
				@lugarpagoid,
				--@departamentoid,
				@Cargo,
				--@Jornada,
				--@Movilizacion,
				--@SueldoBase,
				--@FechaTermino,
				@FechaInicio,
				@FechaIngreso,
				@FechaDocumento,
				@querySiNoObs1,
                --@querySiNoObs2,
                --@querySiNoObs3,
                --@querySiNoObs4,
                --@querySiNoObs5,
                --@querySiNoDinamico1,
                @querySiNoObs1_texto,
                --@querySiNoObs2_texto,
                --@querySiNoObs3_texto,
                --@querySiNoObs4_texto,
                --@querySiNoObs5_texto,
                --@querySiNoDinamico1_texto,
				--@tituloCargo,
                --@nombreEmpleado
                @Ciudad,
                @DescripcionCargo,
                @Duracion,
                @Fecha,
                @ModTeletrabajo,
				@Segmento,
				@PorcentajeBonoTarget,
				@MetaTargetS,
				@MetaTargetU,

				@ObjetivoFinanciero1,
				@DetalleObjetivoFinanciero1,
				@ObjetivoFinanciero2,
				@DetalleObjetivoFinanciero2,
				@MetaIndividual,
				@Bono,
				@Codigo,
				@Texto1,
				@rlRutProveedor,
				@rlTipoDocumento,

				@correoNotificacionPorConcentimiento,

				@CiudadFirma
			)				
				SET @lmensaje = ''
				SET @error = 0						
		END
	ELSE
		BEGIN
			UPDATE ContratoDatosVariables SET 
				idDocumento = @pidDocumento,
				Rut = @Rut,
				CentroCosto = @CentroCosto,
				lugarpagoid = @lugarpagoid,
				--departamentoid = @departamentoid,
				Cargo = @Cargo,
				--Jornada = @Jornada,
				--Movilizacion = @Movilizacion,
				--SueldoBase = @SueldoBase,
				--FechaTermino = @FechaTermino,
				FechaInicio = @FechaInicio,
				FechaIngreso = @FechaIngreso,
				FechaDocumento = @FechaDocumento,
				querySiNoObs1 = @querySiNoObs1,
                --querySiNoObs2 = @querySiNoObs2,
                --querySiNoObs3 = @querySiNoObs3,
                --querySiNoObs4 = @querySiNoObs4,
                --querySiNoObs5 = @querySiNoObs5,
                --querySiNoDinamico1 = @querySiNoDinamico1,
                querySiNoObs1_texto = @querySiNoObs1_texto,
                --querySiNoObs2_texto = @querySiNoObs2_texto,
                --querySiNoObs3_texto = @querySiNoObs3_texto,
                --querySiNoObs4_texto = @querySiNoObs4_texto,
                --querySiNoObs5_texto = @querySiNoObs5_texto,
                --querySiNoDinamico1_texto = @querySiNoDinamico1_texto,
				--tituloCargo = @tituloCargo,
                --nombreEmpleado = @nombreEmpleado
                Ciudad = @Ciudad,
                DescripcionCargo = @DescripcionCargo,
                Duracion = @Duracion,
                Fecha = @Fecha,
                ModTeletrabajo = @ModTeletrabajo,
				Segmento = @Segmento,
				PorcentajeBonoTarget = @PorcentajeBonoTarget,
				MetaTargetS = @MetaTargetS,
				MetaTargetU = @MetaTargetU,

				ObjetivoFinanciero1 = @ObjetivoFinanciero1,
				DetalleObjetivoFinanciero1 = @DetalleObjetivoFinanciero1,
				ObjetivoFinanciero2 = @DetalleObjetivoFinanciero2,
				DetalleObjetivoFinanciero2 = @DetalleObjetivoFinanciero2,
				MetaIndividual = @MetaIndividual,
				Bono = @Bono,
				Codigo = @Codigo,
				Texto1 = @Texto1,
				rlRutProveedor = @rlRutProveedor,
				rlTipoDocumento = @rlTipoDocumento,

				correoNotificacionPorConcentimiento = @correoNotificacionPorConcentimiento,
				
				CiudadFirma = @CiudadFirma
			WHERE 
				idDocumento = @pidDocumento 
				
			SET @lmensaje = ''
			SET @error = 0	
		END 
	SELECT @error AS error, @lmensaje AS mensaje 
END

GO
