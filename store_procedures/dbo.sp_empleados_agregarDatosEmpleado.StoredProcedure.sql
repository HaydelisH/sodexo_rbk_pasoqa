USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_empleados_agregarDatosEmpleado]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 11/06/2019
-- Descripcion: Agregar datos de empleados con Usuario
-- Ejemplo:exec sp_empleados_agregarDatosEmpleado
-- =============================================
CREATE PROCEDURE [dbo].[sp_empleados_agregarDatosEmpleado]
	@ppersonaid VARCHAR (10),
	@pRutEmpresa VARCHAR(10),
	@pidCentroCosto NVARCHAR(14)
AS
BEGIN
	
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	

	--EMPLEADOS
	IF EXISTS (SELECT empleadoid FROM Empleados WHERE empleadoid = @ppersonaid  )      
		BEGIN 
			UPDATE Empleados SET 
				RutEmpresa = @pRutEmpresa,
				centrocostoid = @pidCentroCosto
			WHERE 
				empleadoid = @ppersonaid
					
			SELECT @lmensaje = ''
			SELECT @error = 0
		END	
	
	SELECT @error AS error, @lmensaje AS mensaje
	
END
GO
