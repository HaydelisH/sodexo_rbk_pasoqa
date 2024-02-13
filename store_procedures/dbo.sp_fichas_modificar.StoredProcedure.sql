USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_fichas_modificar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_fichas_modificar]
	@fichaid			INT,
	@empresaid			VARCHAR(10),
	@centrocostoid		NVARCHAR(14),
	@lugarpagoid		NVARCHAR(14),
	@empleadoid			VARCHAR(10), 
	@nacionalidad		VARCHAR(20),
	@nombre			    VARCHAR(110),
	@correo				VARCHAR(100),
	@direccion			VARCHAR(100),
	@ciudad				VARCHAR(50),
	@comuna				VARCHAR(50),
	@fechanacimiento	DATE,
	@estadocivil		INT,
	@fono				VARCHAR(20),
	@idFirma			INT

AS	
BEGIN
	SET NOCOUNT ON;
	
	DECLARE @pcorreo		NVARCHAR(100)
	DECLARE @error		INT
	DECLARE @mensaje	VARCHAR(100)
		
	BEGIN TRANSACTION 
	BEGIN TRY	

	IF EXISTS ( SELECT personaid FROM personas WHERE personaid = @empleadoid ) 
		BEGIN
			--Actualiza los datos
			UPDATE personas SET
				nombre = @nombre,
				nacionalidad = @nacionalidad,
				estadocivil = @estadocivil,
				fechanacimiento = @fechanacimiento,
				direccion = @direccion,
				comuna = @comuna,
				ciudad = @ciudad,
				correo = @correo,
				fono = @fono,
				Eliminado = 0
			WHERE personaid = @empleadoid
			
			SELECT @error = 0
			SELECT @mensaje = ''
		END
		
	IF EXISTS ( SELECT usuarioid FROM usuarios WHERE usuarioid = @empleadoid )
		BEGIN
			UPDATE usuarios SET 
				idFirma = @idFirma 
			WHERE 
				usuarioid = @empleadoid
		END 
		
	--Validar si existe persona en Gestor 
	IF EXISTS ( SELECT personaid FROM  [Smu_Gestor].[dbo].[personas] WHERE personaid = @empleadoid )
		BEGIN 
			--Actualiza los datos
			UPDATE [Smu_Gestor].[dbo].[personas]  SET
				nombre = @nombre,
				nacionalidad = @nacionalidad,
				estadocivil = 	
				--Validar Estado Civil
				CASE 
					WHEN @estadocivil = 1 THEN 'Soltero(a)'
					WHEN @estadocivil = 2 THEN 'Casado(a)'
					WHEN @estadocivil = 3 THEN 'Divorciado(a)'
					WHEN @estadocivil = 4 THEN 'Viudo(a)'
				END,
				fechanacimiento = @fechanacimiento,
				direccion = @direccion,
				comuna = @comuna,
				ciudad = @ciudad,
				correo = @correo,
				fono = @fono
			WHERE personaid = @empleadoid
		END 
			 
	COMMIT TRANSACTION
	END TRY

	BEGIN CATCH
	ROLLBACK TRANSACTION 
	
		SET @error		= ERROR_NUMBER()
		SET @mensaje	= ERROR_MESSAGE()
		
	END CATCH
	
	SELECT @error AS error, @mensaje AS mensaje, @fechanacimiento;
	RETURN;
END
GO
