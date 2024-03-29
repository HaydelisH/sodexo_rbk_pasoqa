USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_firmas_listadoPorGestor]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 05/05/2019
-- Descripcion: Genera el listado de firmas, segun el gestor de firmas
-- =============================================
CREATE PROCEDURE [dbo].[sp_firmas_listadoPorGestor]
	@pGestor INT
AS
BEGIN
	
	IF ( @pGestor = 1 ) --DEC5
		BEGIN 
			SELECT  
				idFirma, 
				Descripcion 
			FROM 
				Firmas 
			WHERE 
				idFirma IN (1,2,5)
		END
	
	IF ( @pGestor = 2 ) --RBK
		BEGIN
			SELECT  
				idFirma, 
				Descripcion 
			FROM 
				Firmas 
			WHERE 
				idFirma < 4
				--idFirma = 1 --PIN
				--idFirma = 2 --Huella
		END
    
    RETURN                                                             

END
GO
