USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_tipofirmas_listado]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 29/06/2018
-- Descripcion: Obtiene los TipoFirmas disponibles de un Contrato
-- Ejemplo:exec sp_tipofirmas_listado
-- =============================================
CREATE PROCEDURE [dbo].[sp_tipofirmas_listado]
AS
BEGIN
	SET NOCOUNT ON;
	BEGIN
		SELECT
 			idTipoFirma,
 			Descripcion
		FROM
			FirmasTipos
        RETURN
    END                                                          
END
GO
