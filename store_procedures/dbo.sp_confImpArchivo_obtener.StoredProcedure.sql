USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_confImpArchivo_obtener]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Macarena Parra Bruna
-- Creado el: 13/06/2018
-- Descripcion: Obtener datos archivo
-- Ejemplo:exec sp_empresas_obtener '9798215-5'
-- =============================================
CREATE PROCEDURE [dbo].[sp_confImpArchivo_obtener]
	@IdArchivo int
AS
BEGIN
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
	
	BEGIN								
		IF EXISTS (SELECT IdArchivo FROM ConfImpArchivo WHERE IdArchivo = @IdArchivo)
			BEGIN
				SELECT 
				       IdArchivo,
				       Archivo, 
				       Entidad,
				       Conexion, 
				       Tipo, 
				       Hoja, 
				       LineasAIgnorar, 
				       Separador
				       
				FROM ConfImpArchivo
				WHERE IdArchivo = @IdArchivo
				
            END 
     END
END
GO
