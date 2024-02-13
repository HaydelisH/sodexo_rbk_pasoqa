USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_tipogeneracion_listado]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez 
-- Creado el: 24-10-2018
-- Descripcion: Obtener listado de Tipos de Generacion
-- Ejemplo:exec sp_tipogeneracion_listado
-- =============================================
CREATE PROCEDURE [dbo].[sp_tipogeneracion_listado]

AS
BEGIN
	
    SELECT idTipoGeneracion, Descripcion FROM TipoGeneracion                 
    RETURN                                                             

END
GO
