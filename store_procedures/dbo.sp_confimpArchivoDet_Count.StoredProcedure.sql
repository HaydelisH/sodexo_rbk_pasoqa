USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_confimpArchivoDet_Count]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Macarena Parra
-- Creado el: 14/08/2018
-- Ejemplo:exec sp_confimpArchivoDet_Count 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_confimpArchivoDet_Count]
@IdArchivo	INT				
	
AS	
BEGIN

SELECT COUNT (*) AS total
  FROM ConfimpArchivoDet WHERE IdArchivo=@IdArchivo
END
GO
