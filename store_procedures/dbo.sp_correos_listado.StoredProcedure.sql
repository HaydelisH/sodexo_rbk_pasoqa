USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_correos_listado]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Macarena Parra Bruna
-- Creado el: 13/06/2018
-- Descripcion: Lista correo
-- Ejemplo:exec sp_correos_listado
-- =============================================
CREATE PROCEDURE [dbo].[sp_correos_listado]
AS
BEGIN
	
    SELECT CodCorreo,Descripcion, CC,CCo,Asunto FROM Correo 
                         
    RETURN                                                             

END
GO
