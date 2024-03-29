USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_fichas_modificar_estado]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez	
-- Creado el: 10/10/2018
-- Descripcion: Modificar el estado de la ficha 
-- Ejemplo:exec sp_fichas_modificar_estado 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_fichas_modificar_estado]
	@pFichaid			INT,
	@pEstado			 INT
AS	
BEGIN
	SET NOCOUNT ON;
 
	IF EXISTS ( SELECT fichaid FROM fichasDatosImportacion WHERE fichaid = @pFichaid )
		BEGIN
			--Modificar los datos en la tabla fichas
			UPDATE fichasDatosImportacion SET
			idEstado = @pEstado
			WHERE fichaid = @pFichaid
		END
	RETURN;
END
GO
