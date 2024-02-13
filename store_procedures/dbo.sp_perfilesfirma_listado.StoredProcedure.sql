USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_perfilesfirma_listado]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: CSB
-- Creado el: 23/07/2019
-- Descripcion:  Listado de los perfiles de firma
-- Ejemplo:exec sp_perfilesfirma_listado
-- =============================================
CREATE PROCEDURE [dbo].[sp_perfilesfirma_listado]
AS
BEGIN
	
    SELECT 
    codigoperfil,
    perfil, 
    descripcion 
    FROM perfilesfirma
                         
    RETURN                                                             

END
GO
