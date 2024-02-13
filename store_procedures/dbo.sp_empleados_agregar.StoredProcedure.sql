USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_empleados_agregar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 10/04/2019
-- Descripcion: Agregar datos de empleados
-- Ejemplo:exec sp_empleados_agregar
-- =============================================
CREATE PROCEDURE [dbo].[sp_empleados_agregar]
	@ppersonaid VARCHAR (10),
	@pnacionalidad VARCHAR(20),
	@pnombre VARCHAR(110),
	@pappaterno VARCHAR(50),
	@papmaterno VARCHAR(50),
	@pcorreo VARCHAR(60),
	@pdireccion VARCHAR(150),
	@pciudad VARCHAR(20),
	@pcomuna VARCHAR(30),
	@pfechanacimiento DATE,
	@pestadocivil INT,
	@prolid INT,
	@idEstado VARCHAR(10)
AS
BEGIN
	
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT;
		
    IF NOT EXISTS ( SELECT personaid FROM personas WHERE personaid = @ppersonaid ) 
		BEGIN 
			INSERT INTO personas(
					personaid, 
					nacionalidad,
					nombre,
					appaterno, 
					apmaterno, 
					correo, 
					direccion, 
					ciudad, 
					comuna,
					fechanacimiento,
					estadocivil,
					Eliminado
				)VALUES(
					@ppersonaid,
					@pnacionalidad,
					@pnombre, 
					@pappaterno, 
					@papmaterno, 
					@pcorreo, 
					@pdireccion,
					@pciudad,
					@pcomuna,
					@pfechanacimiento,
					@pestadocivil,
					0
				)	
				
			SELECT @lmensaje = ''
			SELECT @error = 0			
		END  
	ELSE
		BEGIN 
			UPDATE personas SET 
				nombre = @pnombre,
				appaterno = @pappaterno,
				apmaterno = @papmaterno,
				nacionalidad = @pnacionalidad,
				correo = @pcorreo,
				direccion = @pdireccion,
				comuna = @pcomuna,
				ciudad = @pciudad, 
				fechanacimiento = @pfechanacimiento,
				estadocivil = @pestadocivil,
				Eliminado = 0
			WHERE 
				personaid = @ppersonaid
		END      
		
	IF NOT EXISTS (SELECT empleadoid FROM Empleados WHERE empleadoid = @ppersonaid  )      
		BEGIN 
			INSERT INTO Empleados(empleadoid, rolid, idEstadoEmpleado) VALUES(@ppersonaid, @prolid, @idEstado)
			
			SELECT @lmensaje = ''
			SELECT @error = 0
		END
	ELSE
		BEGIN
			UPDATE Empleados SET 
				rolid = @prolid,
				idEstadoEmpleado = @idEstado
			WHERE 
				empleadoid = @ppersonaid
				
			SELECT @lmensaje = ''
			SELECT @error = 0
		END
	
	 SELECT @error AS error, @lmensaje AS mensaje	
	
END
GO
