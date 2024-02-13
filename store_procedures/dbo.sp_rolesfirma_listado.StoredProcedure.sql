USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_rolesfirma_listado]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: CSB
-- Creado el: 13/05/2019
-- Descripcion:  Listado de los roles de firma
-- Ejemplo:exec sp_rolesfirma_listado
-- =============================================
create PROCEDURE [dbo].[sp_rolesfirma_listado]
AS
BEGIN
	
    SELECT 
    codigorol,
    rol, 
    descripcion 
    FROM rolesfirma
                         
    RETURN                                                             

END
GO
