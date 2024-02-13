USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_confimpArchivoDet_obtener]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Macarena Parra Bruna
-- Creado el: 14/08/2018
-- Descripcion: Obtener datos archivo
-- Ejemplo:exec sp_confimpArchivoDet_obtener 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_confimpArchivoDet_obtener]
	@IdArchivo int
AS
BEGIN
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
	
	BEGIN								
		IF EXISTS (SELECT IdArchivo FROM ConfimpArchivoDet WHERE IdArchivo = @IdArchivo)
			BEGIN
				SELECT 
				       IdArchivo, 
				       Orden, 
				       Nombre, 
				       TipoDato,
				       Ancho, 
				       Obligatorio, 
				       GuardaEnSql, 
				       Formato,
				       EsLlave,
                       NombreExterno
				       
				FROM ConfimpArchivoDet
				WHERE IdArchivo = @IdArchivo
				
            END 
     END
END
GO
