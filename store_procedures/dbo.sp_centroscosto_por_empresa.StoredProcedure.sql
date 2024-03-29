USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_centroscosto_por_empresa]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/14/2018
-- Descripcion: Obtiene el nombre de persona
-- Ejemplo:exec sp_centroscosto_por_empresa
-- =============================================
CREATE PROCEDURE [dbo].[sp_centroscosto_por_empresa]
	@empresaid VARCHAR (10)
AS
BEGIN
	
    SELECT 
		centroscosto.centrocostoid, 
		centroscosto.nombrecentrocosto
	FROM centroscosto 
    WHERE empresaid = @empresaid
    RETURN                                                             

END
GO
