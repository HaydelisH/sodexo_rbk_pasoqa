USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_centrocosto_gestor_agregar]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 15/04/2019
-- Descripcion: Agregar a un centro de costo 
-- Ejemplo:exec sp_centrocosto_gestor_agregar
-- =============================================
CREATE PROCEDURE [dbo].[sp_centrocosto_gestor_agregar]
	@centrocostoid			NVARCHAR(14),
	@nombrecentrocosto		NVARCHAR(50)
AS	
BEGIN
	SET NOCOUNT ON;

	IF NOT EXISTS ( SELECT centrocostoid FROM [SMU_Gestor].[dbo].[centroscosto] WHERE centrocostoid = @centrocostoid ) 
		BEGIN
			--Insertar en la tabla Personas 
			INSERT INTO [SMU_Gestor].[dbo].[centroscosto](centrocostoid, nombrecentrocosto)
				  VALUES(@centrocostoid, @nombrecentrocosto)
		END
		
END
GO
