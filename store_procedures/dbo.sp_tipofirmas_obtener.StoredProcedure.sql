USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_tipofirmas_obtener]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 04/07/2018
-- Descripcion: Obtener Datos de Tipo de Firma a Documenrto 
-- Ejemplo:sp_sp_tipofirmas_obtener 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_tipofirmas_obtener]
	@idTipoFirma INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
    -- Insert statements for procedure here
    BEGIN
		--Consultar existe la palabra Notario en el registro 			
		SELECT 
			idTipoFirma,
			Descripcion
		FROM 
			TipoFirmas
		WHERE 
			idTipoFirma = @idTipoFirma
				   
	END 
END
GO
