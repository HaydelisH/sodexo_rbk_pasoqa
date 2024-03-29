USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_tiposdocumentos_agregar]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 05-04-2019
-- Descripcion:  Agregar un tipo de Proceso 
-- Ejemplo:exec sp_tiposdocumentos_agregar 'xxxx',1,'descripcion'
-- =============================================
CREATE PROCEDURE [dbo].[sp_tiposdocumentos_agregar]
	@pAccion CHAR(60),
	@Descripcion VARCHAR (50)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
    -- Insert statements for procedure here
    IF (@pAccion='agregar')  
    BEGIN
		INSERT INTO TipoDocumentos(NombreTipoDoc,Eliminado) VALUES (@Descripcion, 0) 
		SELECT @@IDENTITY AS idTipoDoc
    END    
END
GO
