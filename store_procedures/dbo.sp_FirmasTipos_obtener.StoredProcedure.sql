USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_FirmasTipos_obtener]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: RC -- COrregir la Plantilla ya que ahora se ve el tipod e documento
-- Creado el: 12/07/2018
-- Modificado por: Gdiaz 11/01/2021
-- Descripcion: Muestra el listado de de Documetnos Generados 
-- Ejemplo:exec [sp_documentos_reportes] '', 2, 1, 10  
-- [sp_FirmasTipos_obtener] 1,1,100,0,0,0,2,0,'','26131316-2',0,NULL,NULL,'','1','1',1
-- Modificado por RC 2020/10/05 AND @empresaid != '0' Modifica condicion por venir empresa con 0
-- =============================================
CREATE PROCEDURE [dbo].[sp_FirmasTipos_obtener]

	@idTipoFirma			INT
AS
BEGIN

	SET NOCOUNT ON;
	
	--Buscar el rol del usuario
	SELECT idTipoFirma FROM FirmasTipos WHERE idTipoFirma = @idTipoFirma		
    RETURN     

END
GO
