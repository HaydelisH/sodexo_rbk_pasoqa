USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_empleados_gestor_agregar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 15/04/2019
-- Descripcion: Agregar a un empleado 
-- Ejemplo:exec sp_empleados_gestor_agregar
-- =============================================
CREATE PROCEDURE [dbo].[sp_empleados_gestor_agregar]
	@empleadoid			NVARCHAR(10),
	@empresaid			NVARCHAR(10),
	@centrocostoid		NVARCHAR(14), 
	@nombre			    VARCHAR(50),
	@appaterno			NVARCHAR(50),
	@apmaterno			NVARCHAR(50),
	@correo				NVARCHAR(100)
AS	
BEGIN
	SET NOCOUNT ON;

	SET @empleadoid = UPPER(@empleadoid)

	IF NOT EXISTS ( SELECT personaid FROM [Smu_Gestor].[dbo].[personas] WHERE personaid = @empleadoid ) 
		BEGIN
			--Insertar en la tabla Personas 
			INSERT INTO [Smu_Gestor].[dbo].[personas](personaid, nombre,appaterno, apmaterno,nacionalidad, estadocivil, fechanacimiento,direccion, comuna, ciudad, region, correo, fono)
				  VALUES(@empleadoid, @nombre,@appaterno,@apmaterno,'','',NULL,'','','','',@correo,'')
		END
		
	IF NOT EXISTS ( SELECT empleadoid FROM [Smu_Gestor].[dbo].[empleados] WHERE empleadoid = @empleadoid ) 
		BEGIN
			--Insertar en tabla de Empleados ***roles=[0,publico],[1,privado] ***estados = [0,VIGENTE],[1,FINIQUITADO]
			INSERT INTO [Smu_Gestor].[dbo].[empleados](empleadoid, empresaid,centrocostoid,rolid, estado,fechaingreso, fechatermino, lugarpagoid,codigoempleado)
				   VALUES(@empleadoid,@empresaid,@centrocostoid,0,0,NULL, NULL,0,NULL)		
		END
END
GO
