USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_firmas_listado]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Macarena Parra Bruna
-- Creado el: 14/06/2018
-- Descripcion: Genera todos los registros
-- =============================================
CREATE PROCEDURE [dbo].[sp_firmas_listado]
AS
BEGIN
	
    SELECT idFirma, Descripcion FROM Firmas 
    RETURN                                                             

END
GO
